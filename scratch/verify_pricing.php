<?php
/**
 * Test script to verify pricing logic
 */

function calculatePrice($price, $adults, $children, $priceType) {
    if ($priceType === 'paquete') {
        return $price;
    } else {
        // Logic: Adults pay full price, children pay 50%
        return ($price * $adults) + ($price * 0.5 * $children);
    }
}

// Case 1: Per Person, 2 Adults, 1 Child. Price $100.
// Should be (100 * 2) + (100 * 0.5 * 1) = 250 (2.5x base)
$p1 = calculatePrice(100, 2, 1, 'persona');
echo "Case 1 (2A, 1C, \$100): $" . $p1 . " - " . ($p1 == 250 ? "PASS" : "FAIL") . "\n";

// Case 2: Per Package, 2 Adults, 5 Children. Price $100.
// Should be 100
$p2 = calculatePrice(100, 2, 5, 'paquete');
echo "Case 2 (2A, 5C, \$100, Fixed): $" . $p2 . " - " . ($p2 == 100 ? "PASS" : "FAIL") . "\n";

// Case 3: Per Person, 1 Adult, 2 Children. Price $50.
// Should be (50 * 1) + (50 * 0.5 * 2) = 50 + 50 = 100 (2x base)
$p3 = calculatePrice(50, 1, 2, 'persona');
echo "Case 3 (1A, 2C, \$50): $" . $p3 . " - " . ($p3 == 100 ? "PASS" : "FAIL") . "\n";
