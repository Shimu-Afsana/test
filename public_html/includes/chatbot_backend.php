<?php
include_once("../database/constants.php");
include_once("manage.php");

if (isset($_POST["message"])) {
    $msg = strtolower(trim($_POST["message"]));
    $m = new Manage();

    if ($msg == "hi" || $msg == "hello") {
        echo "Hello! How can I help you today?";
    } 
    elseif (strpos($msg, "stock") !== false) {
        // Extract product name after 'stock'
        // Example: "stock laptops" => product = "laptops"
        $parts = explode("stock", $msg);
        $productName = trim($parts[1]);

        if (empty($productName)) {
            echo "Please specify the product name after 'stock'. For example, 'stock laptops'.";
            exit();
        }

        // Search product by name (partial match)
        $conn = new mysqli(HOST, USER, PASS, DB);
        $stmt = $conn->prepare("SELECT products_name, products_stock FROM products WHERE products_name LIKE CONCAT('%', ?, '%') LIMIT 1");
        $stmt->bind_param("s", $productName);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo ucfirst($row["products_name"]) . " in stock: " . $row["products_stock"] . " units.";
        } else {
            echo "Sorry, no product found matching '" . htmlspecialchars($productName) . "'.";
        }

        $stmt->close();
        $conn->close();
    }
    elseif (strpos($msg, "low stock") !== false) {
        $conn = new mysqli(HOST, USER, PASS, DB);
        $sql = "SELECT products_name, products_stock FROM products WHERE products_stock < 10";
        $query = $conn->query($sql);

        if ($query->num_rows > 0) {
            echo "Products with low stock:<br>";
            while ($row = $query->fetch_assoc()) {
                echo $row["products_name"] . " - " . $row["products_stock"] . " units<br>";
            }
        } else {
            echo "All products have sufficient stock.";
        }

        $conn->close();
    } else {
        echo "Sorry, I don't understand. Try saying 'hi', 'stock <product name>', or 'low stock'.";
    }
}
?>
