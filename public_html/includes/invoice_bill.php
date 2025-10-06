<?php
session_start();
include_once("../fpdf/fpdf.php");

if (isset($_GET["order_date"]) && isset($_GET["cust_name"])) {

    // 🔍 DEBUG: See exactly what comes in
    echo "<pre>";
    print_r($_GET);
    echo "</pre>";
    // exit(); // Uncomment to stop here and check

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont("Arial", "B", 16);
    $pdf->Cell(190, 10, "Inventory System", 0, 1, "C");
    $pdf->SetFont("Arial", "", 12);

    $pdf->Cell(50, 10, "Date", 0, 0);
    $pdf->Cell(50, 10, ": " . htmlspecialchars($_GET["order_date"]), 0, 1);
    $pdf->Cell(50, 10, "Customer Name", 0, 0);
    $pdf->Cell(50, 10, ": " . htmlspecialchars($_GET["cust_name"]), 0, 1);

    $pdf->Cell(50, 10, "", 0, 1);

    $pdf->Cell(10, 10, "#", 1, 0, "C");
    $pdf->Cell(70, 10, "Product Name", 1, 0, "C");
    $pdf->Cell(30, 10, "Quantity", 1, 0, "C");
    $pdf->Cell(40, 10, "Price", 1, 0, "C");
    $pdf->Cell(40, 10, "Total (Rs)", 1, 1, "C");

    // ✅ Safe loop with checks
    if (isset($_GET["pid"]) && is_array($_GET["pid"])) {
        $rowCount = count($_GET["pid"]);
        for ($i = 0; $i < $rowCount; $i++) {
            $pro_name = isset($_GET["pro_name"][$i]) ? $_GET["pro_name"][$i] : 'N/A';
            $qty = isset($_GET["qty"][$i]) ? floatval($_GET["qty"][$i]) : 0;
            $price = isset($_GET["price"][$i]) ? floatval($_GET["price"][$i]) : 0;
            $total = $qty * $price;

            $pdf->Cell(10, 10, ($i + 1), 1, 0, "C");
            $pdf->Cell(70, 10, htmlspecialchars($pro_name), 1, 0, "C");
            $pdf->Cell(30, 10, $qty, 1, 0, "C");
            $pdf->Cell(40, 10, number_format($price, 2), 1, 0, "C");
            $pdf->Cell(40, 10, number_format($total, 2), 1, 1, "C");
        }
    } else {
        $pdf->Cell(190, 10, "⚠️ No product data found!", 1, 1, "C");
    }

    $pdf->Cell(50, 10, "", 0, 1);

    $pdf->Cell(50, 10, "Sub Total", 0, 0);
    $pdf->Cell(50, 10, ": " . number_format(floatval($_GET["sub_total"] ?? 0), 2), 0, 1);
    $pdf->Cell(50, 10, "GST Tax", 0, 0);
    $pdf->Cell(50, 10, ": " . number_format(floatval($_GET["gst"] ?? 0), 2), 0, 1);
    $pdf->Cell(50, 10, "Discount", 0, 0);
    $pdf->Cell(50, 10, ": " . number_format(floatval($_GET["discount"] ?? 0), 2), 0, 1);
    $pdf->Cell(50, 10, "Net Total", 0, 0);
    $pdf->Cell(50, 10, ": " . number_format(floatval($_GET["net_total"] ?? 0), 2), 0, 1);
    $pdf->Cell(50, 10, "Paid", 0, 0);
    $pdf->Cell(50, 10, ": " . number_format(floatval($_GET["paid"] ?? 0), 2), 0, 1);
    $pdf->Cell(50, 10, "Due Amount", 0, 0);
    $pdf->Cell(50, 10, ": " . number_format(floatval($_GET["due"] ?? 0), 2), 0, 1);
    $pdf->Cell(50, 10, "Payment Type", 0, 0);
    $pdf->Cell(50, 10, ": " . htmlspecialchars($_GET["payment_type"] ?? ''), 0, 1);

    $pdf->Cell(180, 10, "Signature", 0, 0, "R");

    $pdfDir = realpath(__DIR__ . '/../PDF_INVOICE');
    $filePath = $pdfDir . "/PDF_INVOICE_" . $_SESSION["userid"] . ".pdf";

    $pdf->Output($filePath, "F");
    $pdf->Output();
}
?>
