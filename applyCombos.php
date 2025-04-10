<?php
include("dbConnect.php");
header('Content-Type: application/json');
$cart = json_decode(file_get_contents('php://input'), true);
$response = [
    "comboUsage" => [],
    "totalDiscount" => 0
];

if (!$cart) {
    error_log("No cart data received: " . file_get_contents('php://input'));
    echo json_encode(["error" => "No cart data received"]);
    exit;
}


$result = applyCombos($cart, $conn);
$response['comboUsage'] = $result['comboUsage'];
$response['totalDiscount'] = $result['totalDiscount'];

echo json_encode($response);
exit;



function getComboRules($conn) {
    $comboRules = [];
    
    $comboQuery = "SELECT ComboID, comboName, Discount FROM comboTbl";
    $comboResult = $conn->query($comboQuery);

    while ($combo = $comboResult->fetch_assoc()) {
        $comboID = $combo['ComboID'];
        $comboRules[$comboID] = [
            'name' => $combo['comboName'],
            'discount' => $combo['Discount'],
            'items' => [] 
        ];
    }

    $comboItemQuery = "SELECT ComboID, MenuID FROM comboItemTbl";
    $comboItemResult = $conn->query($comboItemQuery);

    while ($comboItem = $comboItemResult->fetch_assoc()) {
        $comboID = $comboItem['ComboID'];
        $menuID = $comboItem['MenuID'];

        $comboRules[$comboID]['items'][] = $menuID;
    }

    return $comboRules;
}

function applyCombos($cart, $conn) {
    $comboRules = getComboRules($conn);

 usort($comboRules, function ($a, $b) {
        return $b['discount'] <=> $a['discount'];
    });

    $comboUsage = [];
    $cartQuantities = []; 
    $totalDiscount = 0;

    foreach ($cart as $item) {
        $cartQuantities[$item['id']] = $item['quantity'];
    }

    foreach ($comboRules as $comboID => $combo) {
        $minComboCount = PHP_INT_MAX; 

        foreach ($combo['items'] as $menuID) {
            if (!isset($cartQuantities[$menuID]) || $cartQuantities[$menuID] == 0) {
                $minComboCount = 0; 
                break;
            }

            $minComboCount = min($minComboCount, floor($cartQuantities[$menuID] / 1)); 
        }

        if ($minComboCount > 0) {
            $comboUsage[$comboID] = [
                'name' => $combo['name'],
                'timesApplied' => $minComboCount,
                'totalDiscount' => $combo['discount'] * $minComboCount
            ];

            foreach ($combo['items'] as $menuID) {
                $cartQuantities[$menuID] -= $minComboCount;
            }
            $totalDiscount += $comboUsage[$comboID]['totalDiscount'];
        }
    }

    return ['comboUsage' => $comboUsage, 'totalDiscount' => $totalDiscount];
}

?>