
<?php
session_start();
date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| ProServe CRM - Printable Invoice
|--------------------------------------------------------------------------
| Session-based invoice document.
| Customers can print this page or save it as a PDF.
|--------------------------------------------------------------------------
*/

function e($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function pdfMoney($amount): string
{
    return 'LKR ' . number_format((float)$amount, 2);
}

function pdfInvoiceReference(array $invoice): string
{
    return (string)(
        $invoice['invoice_reference']
        ?? $invoice['invoice_number']
        ?? 'INV-UNKNOWN'
    );
}

function pdfCustomerName(array $invoice): string
{
    return (string)(
        $invoice['customer_name']
        ?? $invoice['name']
        ?? 'Customer'
    );
}

function pdfServiceName(array $invoice): string
{
    return (string)(
        $invoice['service']
        ?? $invoice['service_name']
        ?? 'Professional Services'
    );
}

function pdfStatusClass(string $status): string
{
    switch (strtoupper($status)) {
        case 'PAID':
            return 'paid';

        case 'PARTIAL':
            return 'partial';

        case 'OVERDUE':
            return 'overdue';

        case 'CANCELLED':
            return 'cancelled';

        case 'DRAFT':
            return 'draft';

        default:
            return 'unpaid';
    }
}

function pdfIsOverdue(array $invoice): bool
{
    $status = strtoupper((string)($invoice['status'] ?? ''));

    if (in_array($status, ['PAID', 'CANCELLED', 'DRAFT'], true)) {
        return false;
    }

    $dueDate = (string)($invoice['due_date'] ?? '');

    return $dueDate !== ''
        && strtotime($dueDate) !== false
        && strtotime($dueDate) < strtotime(date('Y-m-d'));
}

function pdfDate($date): string
{
    if (!$date || strtotime((string)$date) === false) {
        return '—';
    }

    return date('d M Y', strtotime((string)$date));
}

/*
|--------------------------------------------------------------------------
| Find the requested invoice
|--------------------------------------------------------------------------
*/

$invoices = $_SESSION['proserve_invoices'] ?? [];

$requestedReference = trim(
    (string)($_GET['invoice'] ?? $_GET['ref'] ?? '')
);

$invoice = null;

if ($requestedReference !== '') {
    foreach ($invoices as $item) {
        if (
            pdfInvoiceReference($item) === $requestedReference
        ) {
            $invoice = $item;
            break;
        }
    }
}

/*
|--------------------------------------------------------------------------
| Optional fallback for direct page testing
|--------------------------------------------------------------------------
| If invoice_pdf.php is opened without an invoice reference, display
| the first available invoice in the current session.
|--------------------------------------------------------------------------
*/

if ($invoice === null && $requestedReference === '' && !empty($invoices)) {
    $invoice = reset($invoices);
}

/*
|--------------------------------------------------------------------------
| Return an error if no matching invoice exists
|--------------------------------------------------------------------------
*/

if ($invoice === null) {
    http_response_code(404);
    $pageError = $requestedReference !== ''
        ? 'The requested invoice could not be found in your current demo session.'
        : 'No invoices are available. Please create an invoice first.';
} else {
    $pageError = null;
}

/*
|--------------------------------------------------------------------------
| Invoice calculations
|--------------------------------------------------------------------------
*/

if ($invoice !== null) {
    $reference = pdfInvoiceReference($invoice);
    $customerName = pdfCustomerName($invoice);
    $serviceName = pdfServiceName($invoice);

    $status = strtoupper((string)($invoice['status'] ?? 'UNPAID'));
    $overdue = pdfIsOverdue($invoice);

    if ($overdue) {
        $displayStatus = 'OVERDUE';
    } else {
        $displayStatus = $status;
    }

    $amount = (float)(
        $invoice['amount']
        ?? $invoice['total_amount']
        ?? 0
    );

    $paidAmount = $status === 'PAID'
        ? $amount
        : (float)($invoice['paid_amount'] ?? 0);

    $paidAmount = max(0, min($paidAmount, $amount));

    $outstanding = max(0, $amount - $paidAmount);

    $company = trim((string)($invoice['company'] ?? ''));
    $email = trim((string)($invoice['email'] ?? ''));
    $quotationReference = trim(
        (string)($invoice['quotation_reference'] ?? '')
    );

    $issueDate = $invoice['issue_date'] ?? '';
    $dueDate = $invoice['due_date'] ?? '';
    $paymentMethod = trim(
        (string)($invoice['payment_method'] ?? 'Not specified')
    );

    $notes = trim((string)($invoice['notes'] ?? ''));

    $statusClass = pdfStatusClass($displayStatus);

    $invoiceDateForFilename = preg_replace(
        '/[^A-Za-z0-9_-]/',
        '_',
        $reference
    );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= $invoice !== null
            ? 'Invoice ' . e($reference) . ' | ProServe'
            : 'Invoice Not Found | ProServe'
        ?>
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --navy: #10233f;
            --muted: #64748b;
            --border: #e5eaf2;
            --background: #f3f6fb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 28px 16px;
            background: var(--background);
            color: #1e293b;
            font-family: 'Inter', 'Trebuchet MS', sans-serif;
            font-size: 13px;
        }

        .toolbar {
            max-width: 900px;
            margin: 0 auto 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .toolbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 19px;
            font-weight: 800;
            color: var(--navy);
        }

        .toolbar-brand-icon {
            display: grid;
            place-items: center;
            width: 40px;
            height: 40px;
            border-radius: 11px;
            color: #fff;
            background: var(--primary);
            font-size: 20px;
        }

        .toolbar-actions {
            display: flex;
            gap: 9px;
            flex-wrap: wrap;
        }

        .btn {
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            padding: 10px 14px;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .invoice-paper {
            width: 100%;
            max-width: 900px;
            min-height: 950px;
            margin: 0 auto;
            padding: 55px 58px;
            background: #fff;
            border: 1px solid #e6eaf1;
            border-radius: 5px;
            box-shadow: 0 15px 45px rgba(15, 23, 42, .07);
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 25px;
            padding-bottom: 32px;
            border-bottom: 2px solid var(--navy);
        }

        .company-brand {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .company-logo {
            display: grid;
            place-items: center;
            width: 54px;
            height: 54px;
            border-radius: 13px;
            background: var(--primary);
            color: #fff;
            font-size: 27px;
        }

        .company-name {
            color: var(--navy);
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .company-tagline {
            color: var(--muted);
            font-size: 10px;
            margin-top: 5px;
        }

        .company-contact {
            color: var(--muted);
            font-size: 11px;
            line-height: 1.9;
            margin-top: 20px;
        }

        .invoice-title {
            color: var(--primary);
            font-size: 35px;
            font-weight: 800;
            letter-spacing: 1px;
            text-align: right;
        }

        .invoice-number {
            text-align: right;
            font-size: 12px;
            font-weight: 700;
            margin-top: 6px;
            overflow-wrap: anywhere;
        }

        .invoice-reference-small {
            text-align: right;
            font-size: 11px;
            color: var(--muted);
            margin-top: 8px;
        }

        .status-pill {
            display: inline-block;
            padding: 7px 11px;
            margin-top: 14px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .status-partial {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-unpaid {
            background: #fef3c7;
            color: #92400e;
        }

        .status-overdue {
            background: #fee2e2;
            color: #b91c1c;
        }

        .status-cancelled {
            background: #e2e8f0;
            color: #334155;
        }

        .status-draft {
            background: #f1f5f9;
            color: #475569;
        }

        .section {
            margin-top: 35px;
        }

        .section-label {
            margin-bottom: 13px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .bill-to-name {
            color: var(--navy);
            font-size: 17px;
            font-weight: 800;
            margin-bottom: 8px;
            overflow-wrap: anywhere;
        }

        .customer-detail {
            color: #526174;
            font-size: 12px;
            line-height: 1.9;
            overflow-wrap: anywhere;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
        }

        .meta-item {
            padding: 15px;
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 9px;
        }

        .meta-label {
            color: var(--muted);
            font-size: 10px;
            margin-bottom: 7px;
        }

        .meta-value {
            color: #1e293b;
            font-size: 12px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        .invoice-table thead th {
            background: var(--navy);
            color: #fff;
            font-size: 10px;
            letter-spacing: .5px;
            text-transform: uppercase;
            text-align: left;
            padding: 15px 13px;
        }

        .invoice-table thead th:last-child,
        .invoice-table tbody td:last-child {
            text-align: right;
        }

        .invoice-table tbody td {
            padding: 20px 13px;
            border-bottom: 1px solid var(--border);
            vertical-align: top;
        }

        .item-title {
            font-size: 12px;
            font-weight: 800;
            color: var(--navy);
        }

        .item-description {
            font-size: 11px;
            line-height: 1.8;
            color: var(--muted);
            margin-top: 6px;
        }

        .table-amount {
            font-weight: 800;
            white-space: nowrap;
        }

        .totals-wrap {
            display: flex;
            justify-content: flex-end;
            margin-top: 24px;
        }

        .totals {
            width: 100%;
            max-width: 330px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 11px 0;
            border-bottom: 1px solid var(--border);
            font-size: 12px;
        }

        .total-row span:first-child {
            color: var(--muted);
        }

        .total-row strong {
            text-align: right;
            white-space: nowrap;
        }

        .total-row.grand-total {
            border-bottom: 0;
            margin-top: 5px;
            padding: 16px;
            border-radius: 9px;
            background: #eff5ff;
        }

        .total-row.grand-total span:first-child {
            color: var(--navy);
            font-weight: 800;
        }

        .total-row.grand-total strong {
            color: var(--primary);
            font-size: 18px;
        }

        .payment-details {
            padding: 17px;
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 9px;
            margin-top: 27px;
        }

        .payment-details h6 {
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .payment-detail-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 7px 0;
            font-size: 11px;
        }

        .payment-detail-row span:first-child {
            color: var(--muted);
        }

        .payment-detail-row strong {
            text-align: right;
            overflow-wrap: anywhere;
        }

        .notes-box {
            padding: 15px;
            border-left: 3px solid var(--primary);
            background: #f8fafc;
            color: #526174;
            font-size: 11px;
            line-height: 1.8;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .thank-you {
            margin-top: 42px;
            text-align: center;
            color: var(--navy);
            font-size: 16px;
            font-weight: 800;
        }

        .thank-you-subtitle {
            margin-top: 8px;
            text-align: center;
            color: var(--muted);
            font-size: 11px;
        }

        .invoice-footer {
            margin-top: 40px;
            padding-top: 18px;
            border-top: 1px solid var(--border);
            text-align: center;
            color: var(--muted);
            font-size: 10px;
            line-height: 1.9;
        }

        .error-card {
            max-width: 650px;
            margin: 80px auto;
            background: #fff;
            padding: 35px;
            border: 1px solid var(--border);
            border-radius: 14px;
            text-align: center;
        }

        .error-card i {
            color: #dc2626;
            font-size: 40px;
        }

        .error-card h2 {
            margin-top: 15px;
            font-size: 21px;
            font-weight: 800;
        }

        .error-card p {
            color: var(--muted);
            line-height: 1.8;
        }

        @media (max-width: 650px) {
            body {
                padding: 12px 8px;
            }

            .invoice-paper {
                padding: 25px 18px;
                min-height: auto;
            }

            .invoice-header {
                flex-direction: column;
                gap: 25px;
            }

            .invoice-title,
            .invoice-number,
            .invoice-reference-small {
                text-align: left;
            }

            .invoice-title {
                font-size: 30px;
            }

            .meta-grid {
                grid-template-columns: 1fr;
            }

            .toolbar {
                padding: 0 4px;
            }

            .toolbar-actions {
                width: 100%;
            }

            .toolbar-actions .btn {
                flex: 1;
            }

            .invoice-table thead th,
            .invoice-table tbody td {
                padding: 11px 8px;
            }

            .invoice-table {
                font-size: 11px;
            }
        }

        @page {
            size: A4;
            margin: 12mm;
        }

        @media print {
            body {
                background: #fff !important;
                padding: 0;
                margin: 0;
                font-size: 11px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .toolbar,
            .no-print {
                display: none !important;
            }

            .invoice-paper {
                width: 100%;
                max-width: none;
                min-height: 0;
                padding: 5mm;
                border: 0;
                border-radius: 0;
                box-shadow: none;
                margin: 0;
            }

            .section {
                margin-top: 7mm;
            }

            .invoice-header {
                padding-bottom: 7mm;
            }

            .invoice-table thead {
                display: table-header-group;
            }

            .invoice-table tr,
            .meta-item,
            .payment-details,
            .notes-box {
                break-inside: avoid;
            }

            .thank-you {
                margin-top: 9mm;
            }

            .invoice-footer {
                margin-top: 8mm;
            }

            a {
                color: inherit !important;
                text-decoration: none !important;
            }
        }
    </style>
</head>

<body>

<?php if ($invoice === null): ?>

    <div class="error-card">
        <i class="bi bi-file-earmark-x"></i>

        <h2>Invoice Not Found</h2>

        <p><?= e($pageError) ?></p>

        <p>
            Invoice documents use the current PHP session. Make sure you open
            this page in the same browser session where the invoice was created.
        </p>

        <a href="invoices.php" class="btn btn-primary mt-2">
            <i class="bi bi-arrow-left me-1"></i> Return to Invoices
        </a>
    </div>

<?php else: ?>

    <!-- Top Toolbar -->
    <div class="toolbar no-print">
        <div class="toolbar-brand">
            <div class="toolbar-brand-icon">
                <i class="bi bi-grid-1x2-fill"></i>
            </div>
            <div>ProServe <span class="text-muted fw-normal">/ Invoice</span></div>
        </div>

        <div class="toolbar-actions">
            <a href="invoices.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Invoices
            </a>

            <button type="button" class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-download me-1"></i> Print / Save PDF
            </button>
        </div>
    </div>

    <!-- Printable Invoice -->
    <article class="invoice-paper">

        <!-- Invoice Header -->
        <header class="invoice-header">
            <div>
                <div class="company-brand">
                    <div class="company-logo">
                        <i class="bi bi-grid-1x2-fill"></i>
                    </div>

                    <div>
                        <div class="company-name">ProServe</div>
                        <div class="company-tagline">
                            CRM &amp; Service Management Solutions
                        </div>
                    </div>
                </div>

                <div class="company-contact">
                    <strong>ProServe — Professional Services</strong><br>
                    Web Development &amp; Business Solutions<br>
                    Email: info@proserve.demo<br>
                    Website: www.proserve.demo
                </div>
            </div>

            <div>
                <div class="invoice-title">INVOICE</div>

                <div class="invoice-number">
                    <?= e($reference) ?>
                </div>

                <?php if ($quotationReference !== ''): ?>
                    <div class="invoice-reference-small">
                        Quotation: <?= e($quotationReference) ?>
                    </div>
                <?php endif; ?>

                <div class="text-end">
                    <span class="status-pill status-<?= e($statusClass) ?>">
                        <?= e($displayStatus) ?>
                    </span>
                </div>
            </div>
        </header>

        <!-- Customer and Dates -->
        <section class="section">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="section-label">Invoice To</div>

                    <div class="bill-to-name">
                        <?= e($customerName) ?>
                    </div>

                    <div class="customer-detail">
                        <?php if ($company !== ''): ?>
                            <div><?= e($company) ?></div>
                        <?php endif; ?>

                        <?php if ($email !== ''): ?>
                            <div>
                                <i class="bi bi-envelope me-1"></i>
                                <?= e($email) ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($company === '' && $email === ''): ?>
                            <div>Customer information recorded in ProServe.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="section-label">Invoice Information</div>

                    <div class="meta-grid">
                        <div class="meta-item">
                            <div class="meta-label">Invoice Date</div>
                            <div class="meta-value"><?= e(pdfDate($issueDate)) ?></div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-label">Payment Due</div>
                            <div class="meta-value"><?= e(pdfDate($dueDate)) ?></div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-label">Invoice Number</div>
                            <div class="meta-value"><?= e($reference) ?></div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-label">Payment Method</div>
                            <div class="meta-value"><?= e($paymentMethod) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Invoice Items -->
        <section class="section">
            <div class="section-label">Services Provided</div>

            <table class="invoice-table">
                <thead>
                    <tr>
                        <th style="width: 65%;">Description</th>
                        <th style="width: 35%; text-align:right;">Amount</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>
                            <div class="item-title"><?= e($serviceName) ?></div>

                            <div class="item-description">
                                Professional service delivery and implementation
                                according to the agreed project requirements.
                            </div>
                        </td>

                        <td class="table-amount">
                            <?= e(pdfMoney($amount)) ?>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Totals -->
            <div class="totals-wrap">
                <div class="totals">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <strong><?= e(pdfMoney($amount)) ?></strong>
                    </div>

                    <div class="total-row">
                        <span>Tax / Additional Charges</span>
                        <strong><?= e(pdfMoney(0)) ?></strong>
                    </div>

                    <div class="total-row grand-total">
                        <span>Total Amount</span>
                        <strong><?= e(pdfMoney($amount)) ?></strong>
                    </div>

                    <div class="total-row">
                        <span>Amount Paid</span>
                        <strong class="text-success"><?= e(pdfMoney($paidAmount)) ?></strong>
                    </div>

                    <div class="total-row">
                        <span>Outstanding Balance</span>
                        <strong class="<?= $outstanding > 0 ? 'text-danger' : 'text-success' ?>">
                            <?= e(pdfMoney($outstanding)) ?>
                        </strong>
                    </div>
                </div>
            </div>
        </section>

        <!-- Payment Details -->
        <section class="payment-details">
            <h6><i class="bi bi-credit-card me-2 text-primary"></i>Payment Summary</h6>

            <div class="payment-detail-row">
                <span>Payment Status</span>
                <strong><?= e($displayStatus) ?></strong>
            </div>

            <div class="payment-detail-row">
                <span>Payment Method</span>
                <strong><?= e($paymentMethod) ?></strong>
            </div>

            <div class="payment-detail-row">
                <span>Amount Paid</span>
                <strong><?= e(pdfMoney($paidAmount)) ?></strong>
            </div>

            <div class="payment-detail-row">
                <span>Remaining Balance</span>
                <strong><?= e(pdfMoney($outstanding)) ?></strong>
            </div>
        </section>

        <!-- Notes -->
        <?php if ($notes !== ''): ?>
            <section class="section">
                <div class="section-label">Additional Notes</div>

                <div class="notes-box"><?= e($notes) ?></div>
            </section>
        <?php endif; ?>

        <!-- Footer -->
        <div class="thank-you">
            Thank you for choosing ProServe!
        </div>

        <div class="thank-you-subtitle">
            We appreciate your business and look forward to working with you.
        </div>

        <footer class="invoice-footer">
            <strong>ProServe — CRM &amp; Service Management System</strong><br>
            Portfolio demonstration by Codvanta.<br>
            This document was generated by the ProServe demonstration system.
            Please contact the service provider to verify payment details.
        </footer>

    </article>

<?php endif; ?>

</body>
</html>