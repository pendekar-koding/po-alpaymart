<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\ActivityLogModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Orders extends BaseController
{
    protected $orderModel;
    protected $activityLogModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->activityLogModel = new ActivityLogModel();
    }

    public function index()
    {
        if (!session()->get('user_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $userId = session()->get('user_id');
        $role = session()->get('role');
        $search = $this->request->getGet('search');

        if ($role === 'seller') {
            $orders = $this->orderModel->getOrdersBySeller($userId, $search);
        } else {
            $orders = $this->orderModel->getOrdersWithCustomer($search);
        }

        $data = [
            'orders' => $orders,
            'search' => $search,
            'title' => 'Pesanan'
        ];

        return view('admin/orders/index', $data);
    }

    public function view($id)
    {
        if (!session()->get('user_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $order = $this->orderModel->select('customer_orders.*, divisions.nama_divisi')
                                  ->join('divisions', 'divisions.id = customer_orders.division_id')
                                  ->where('customer_orders.id', $id)
                                  ->first();

        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        $db = \Config\Database::connect();
        $orderItems = $db->table('customer_order_items')
                        ->select('customer_order_items.*, product_variants.variant_name, products.name as product_name, users.shop_name')
                        ->join('product_variants', 'product_variants.id = customer_order_items.product_variant_id')
                        ->join('products', 'products.id = product_variants.product_id')
                        ->join('users', 'users.id = products.user_id')
                        ->where('order_id', $id)
                        ->get()->getResult();

        $data = [
            'order' => $order,
            'order_items' => $orderItems
        ];

        return view('admin/orders/view', $data);
    }

    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        $this->orderModel->update($id, ['status' => $status]);
        
        $this->activityLogModel->logActivity(
            session()->get('user_id'),
            session()->get('username'),
            session()->get('role'),
            'Update Order Status',
            "Updated order ID {$id} status to {$status}"
        );
        
        return redirect()->back()->with('success', 'Status order berhasil diupdate');
    }

    public function delete($id)
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/admin/orders')->with('error', 'Akses ditolak');
        }

        // Generate backup before delete
        $this->generateBackupReports();

        $db = \Config\Database::connect();
        $db->table('customer_order_items')->where('order_id', $id)->delete();
        $this->orderModel->delete($id);
        
        $this->activityLogModel->logActivity(
            session()->get('user_id'),
            session()->get('username'),
            session()->get('role'),
            'Delete Order',
            "Deleted order ID {$id}"
        );
        
        return redirect()->to('/admin/orders')->with('success', 'Pesanan berhasil dihapus');
    }

    public function deleteAll()
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/admin/orders')->with('error', 'Akses ditolak');
        }

        // Generate backup before delete
        $this->generateBackupReports();

        $db = \Config\Database::connect();
        $db->table('customer_order_items')->truncate();
        $db->table('customer_orders')->truncate();
        
        $this->activityLogModel->logActivity(
            session()->get('user_id'),
            session()->get('username'),
            session()->get('role'),
            'Delete All Orders',
            'Deleted all orders from system'
        );
        
        return redirect()->to('/admin/orders')->with('success', 'Semua pesanan berhasil dihapus');
    }

    public function exportExcel()
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/admin/orders')->with('error', 'Akses ditolak');
        }

        $orders = $this->orderModel->getOrdersWithCustomerOrderbyASC();
        $db = \Config\Database::connect();
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $sheet->setCellValue('A1', 'No. Pesanan');
        $sheet->setCellValue('B1', 'Nama Customer');
        $sheet->setCellValue('C1', 'Divisi');
        $sheet->setCellValue('D1', 'WhatsApp');
        $sheet->setCellValue('E1', 'Status');
        $sheet->setCellValue('F1', 'Metode Pembayaran');
        $sheet->setCellValue('G1', 'Total Pembayaran');
        $sheet->setCellValue('H1', 'Produk');
        $sheet->setCellValue('I1', 'Qty');
        $sheet->setCellValue('J1', 'Note');
        $sheet->setCellValue('K1', 'Toko');
        
        // Style headers
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        $sheet->getStyle('A1:K1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('E2E8F0');
        
        $row = 2;
        foreach ($orders as $order) {
            $orderItems = $db->table('customer_order_items')
                            ->select('customer_order_items.*, product_variants.variant_name, products.name as product_name, users.shop_name')
                            ->join('product_variants', 'product_variants.id = customer_order_items.product_variant_id')
                            ->join('products', 'products.id = product_variants.product_id')
                            ->join('users', 'users.id = products.user_id')
                            ->where('order_id', $order['id'])
                            ->get()->getResult();
            
            $startRow = $row;
            foreach ($orderItems as $item) {
                $sheet->setCellValue('A' . $row, $order['order_number']);
                $sheet->setCellValue('B' . $row, $order['customer_name']);
                $sheet->setCellValue('C' . $row, $order['nama_divisi'] ?? 'N/A');
                $sheet->setCellValue('D' . $row, $order['customer_whatsapp']);
                $sheet->setCellValue('E' . $row, ucfirst($order['status']));
                $sheet->setCellValue('F' . $row, $order['payment_method']);
                $sheet->setCellValue('G' . $row, 'Rp ' . number_format($order['total_amount'], 0, ',', '.'));
                $sheet->setCellValue('H' . $row, $item->product_name . ' - ' . $item->variant_name);
                $sheet->setCellValue('I' . $row, $item->quantity);
                $sheet->setCellValue('J' . $row, $item->note);
                $sheet->setCellValue('K' . $row, $item->shop_name ?? '-');
                $row++;
            }
            
            // Merge cells for order info
            if (count($orderItems) > 1) {
                $endRow = $row - 1;
                $sheet->mergeCells('A' . $startRow . ':A' . $endRow);
                $sheet->mergeCells('B' . $startRow . ':B' . $endRow);
                $sheet->mergeCells('C' . $startRow . ':C' . $endRow);
                $sheet->mergeCells('D' . $startRow . ':D' . $endRow);
                $sheet->mergeCells('E' . $startRow . ':E' . $endRow);
                $sheet->mergeCells('F' . $startRow . ':F' . $endRow);
                $sheet->mergeCells('G' . $startRow . ':G' . $endRow);
                
                // Center align merged cells
                $sheet->getStyle('A' . $startRow . ':G' . $endRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            }
        }
        
        // Auto-size columns
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Laporan_Pesanan_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        $this->activityLogModel->logActivity(
            session()->get('user_id'),
            session()->get('username'),
            session()->get('role'),
            'Export Excel',
            'Exported all orders to Excel'
        );
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    public function exportSellerExcel()
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'seller') {
            return redirect()->to('/admin/orders')->with('error', 'Akses ditolak');
        }

        $userId = session()->get('user_id');
        $db = \Config\Database::connect();
        
        // Get seller's shop name
        $user = $db->table('users')->where('id', $userId)->get()->getRow();
        $shopName = $user->shop_name ?? 'Toko';
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $sheet->setCellValue('A1', 'No. Order');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Divisi');
        $sheet->setCellValue('D1', 'WhatsApp');
        $sheet->setCellValue('E1', 'Total Harga');
        $sheet->setCellValue('F1', 'Produk');
        $sheet->setCellValue('G1', 'Catatan');
        $sheet->setCellValue('H1', 'Qty');
        
        // Style headers
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('E2E8F0');
        
        // Get orders grouped by order_id for this seller
        $orders = $db->table('customer_orders')
                    ->select('customer_orders.id, customer_orders.order_number, customer_orders.customer_name, divisions.nama_divisi, customer_orders.customer_whatsapp, customer_orders.total_amount')
                    ->join('customer_order_items', 'customer_order_items.order_id = customer_orders.id')
                    ->join('product_variants', 'product_variants.id = customer_order_items.product_variant_id')
                    ->join('products', 'products.id = product_variants.product_id')
                    ->join('divisions', 'divisions.id = customer_orders.division_id')
                    ->where('products.user_id', $userId)
                    ->groupBy('customer_orders.id')
                    ->orderBy('customer_orders.order_number')
                    ->get()->getResult();
        
        $row = 2;
        foreach ($orders as $order) {
            // Get order items for this order
            $orderItems = $db->table('customer_order_items')
                            ->select('customer_order_items.*, product_variants.variant_name, products.name as product_name')
                            ->join('product_variants', 'product_variants.id = customer_order_items.product_variant_id')
                            ->join('products', 'products.id = product_variants.product_id')
                            ->where('customer_order_items.order_id', $order->id)
                            ->where('products.user_id', $userId)
                            ->get()->getResult();
            
            $startRow = $row;
            foreach ($orderItems as $item) {
                $sheet->setCellValue('A' . $row, $order->order_number);
                $sheet->setCellValue('B' . $row, $order->customer_name);
                $sheet->setCellValue('C' . $row, $order->nama_divisi ?? 'N/A');
                $sheet->setCellValue('D' . $row, $order->customer_whatsapp);
                $sheet->setCellValue('E' . $row, 'Rp ' . number_format($order->total_amount, 0, ',', '.'));
                $sheet->setCellValue('F' . $row, $item->product_name . ' - ' . $item->variant_name);
                $sheet->setCellValue('G' . $row, $item->note ?? '-');
                $sheet->setCellValue('H' . $row, $item->quantity);
                $row++;
            }
            
            // Merge cells for order info if multiple items
            if (count($orderItems) > 1) {
                $endRow = $row - 1;
                $sheet->mergeCells('A' . $startRow . ':A' . $endRow);
                $sheet->mergeCells('B' . $startRow . ':B' . $endRow);
                $sheet->mergeCells('C' . $startRow . ':C' . $endRow);
                $sheet->mergeCells('D' . $startRow . ':D' . $endRow);
                $sheet->mergeCells('E' . $startRow . ':E' . $endRow);
                
                // Center align merged cells
                $sheet->getStyle('A' . $startRow . ':E' . $endRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            }
        }
        
        // Auto-size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Laporan_Pesanan_' . $shopName . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        $this->activityLogModel->logActivity(
            session()->get('user_id'),
            session()->get('username'),
            session()->get('role'),
            'Export Seller Excel',
            "Exported orders for shop: {$shopName}"
        );
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    public function exportPdf()
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/admin/orders')->with('error', 'Akses ditolak');
        }

        $db = \Config\Database::connect();
        
        // Get orders grouped by shop
        $shops = $db->table('users')
                   ->select('users.id, users.shop_name')
                   ->where('role', 'seller')
                   ->where('shop_name IS NOT NULL')
                   ->get()->getResult();

        $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
        
        foreach ($shops as $index => $shop) {
            $orderItems = $db->table('customer_order_items')
                            ->select('customer_orders.order_number, customer_orders.customer_name, divisions.nama_divisi, customer_orders.customer_whatsapp, products.name as product_name, product_variants.variant_name, customer_order_items.quantity, customer_order_items.note')
                            ->join('customer_orders', 'customer_orders.id = customer_order_items.order_id')
                            ->join('divisions', 'divisions.id = customer_orders.division_id')
                            ->join('product_variants', 'product_variants.id = customer_order_items.product_variant_id')
                            ->join('products', 'products.id = product_variants.product_id')
                            ->where('products.user_id', $shop->id)
                            ->orderBy('customer_orders.order_number')
                            ->get()->getResult();
            
            if (empty($orderItems)) continue;
            
            if ($index > 0) $mpdf->AddPage();
            
            $html = '<h2 style="text-align: center; margin-bottom: 20px;">Laporan Pesanan - ' . $shop->shop_name . '</h2>';
            $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">';
            $html .= '<thead><tr style="background-color: #f0f0f0;">';
            $html .= '<th>No. Order</th><th>Nama</th><th>Divisi</th><th>WhatsApp</th><th>Produk</th><th>Catatan</th><th>Qty</th>';
            $html .= '</tr></thead><tbody>';
            
            foreach ($orderItems as $item) {
                $html .= '<tr>';
                $html .= '<td>' . $item->order_number . '</td>';
                $html .= '<td>' . $item->customer_name . '</td>';
                $html .= '<td>' . ($item->nama_divisi ?? 'N/A') . '</td>';
                $html .= '<td>' . $item->customer_whatsapp . '</td>';
                $html .= '<td>' . $item->product_name . ' - ' . $item->variant_name . '</td>';
                $html .= '<td>' . ($item->note ?? '-') . '</td>';
                $html .= '<td>' . $item->quantity . '</td>';
                $html .= '</tr>';
            }
            
            $html .= '</tbody></table>';
            $mpdf->WriteHTML($html);
        }
        
        $this->activityLogModel->logActivity(
            session()->get('user_id'),
            session()->get('username'),
            session()->get('role'),
            'Export PDF',
            'Exported orders per shop to PDF'
        );
        
        $filename = 'Laporan_Pesanan_Per_Toko_' . date('Y-m-d_H-i-s') . '.pdf';
        $mpdf->Output($filename, 'D');
    }

    private function generateBackupReports()
    {
        $reportPath = WRITEPATH . 'report';
        if (!is_dir($reportPath)) {
            mkdir($reportPath, 0755, true);
        }

        // Generate all orders backup
        $this->generateAllOrdersBackup($reportPath);
        
        // Generate per shop backup
        $this->generatePerShopBackup($reportPath);
    }

    private function generateAllOrdersBackup($reportPath)
    {
        $orders = $this->orderModel->getOrdersWithCustomerOrderbyASC();
        $db = \Config\Database::connect();
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'No. Pesanan');
        $sheet->setCellValue('B1', 'Nama Customer');
        $sheet->setCellValue('C1', 'Divisi');
        $sheet->setCellValue('D1', 'WhatsApp');
        $sheet->setCellValue('E1', 'Status');
        $sheet->setCellValue('F1', 'Metode Pembayaran');
        $sheet->setCellValue('G1', 'Total Harga');
        $sheet->setCellValue('H1', 'Produk');
        $sheet->setCellValue('I1', 'Qty');
        $sheet->setCellValue('J1', 'Note');
        $sheet->setCellValue('K1', 'Toko');
        
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        
        $row = 2;
        foreach ($orders as $order) {
            $orderItems = $db->table('customer_order_items')
                            ->select('customer_order_items.*, product_variants.variant_name, products.name as product_name, users.shop_name')
                            ->join('product_variants', 'product_variants.id = customer_order_items.product_variant_id')
                            ->join('products', 'products.id = product_variants.product_id')
                            ->join('users', 'users.id = products.user_id')
                            ->where('order_id', $order['id'])
                            ->get()->getResult();
            
            $startRow = $row;
            foreach ($orderItems as $item) {
                $sheet->setCellValue('A' . $row, $order['order_number']);
                $sheet->setCellValue('B' . $row, $order['customer_name']);
                $sheet->setCellValue('C' . $row, $order['nama_divisi'] ?? 'N/A');
                $sheet->setCellValue('D' . $row, $order['customer_whatsapp']);
                $sheet->setCellValue('E' . $row, ucfirst($order['status']));
                $sheet->setCellValue('F' . $row, $order['payment_method']);
                $sheet->setCellValue('G' . $row, 'Rp ' . number_format($order['total_amount'], 0, ',', '.'));
                $sheet->setCellValue('H' . $row, $item->product_name . ' - ' . $item->variant_name);
                $sheet->setCellValue('I' . $row, $item->quantity);
                $sheet->setCellValue('J' . $row, $item->note);
                $sheet->setCellValue('K' . $row, $item->shop_name ?? '-');
                $row++;
            }
            
            // Merge cells for order info if multiple items
            if (count($orderItems) > 1) {
                $endRow = $row - 1;
                $sheet->mergeCells('A' . $startRow . ':A' . $endRow);
                $sheet->mergeCells('B' . $startRow . ':B' . $endRow);
                $sheet->mergeCells('C' . $startRow . ':C' . $endRow);
                $sheet->mergeCells('D' . $startRow . ':D' . $endRow);
                $sheet->mergeCells('E' . $startRow . ':E' . $endRow);
                $sheet->mergeCells('F' . $startRow . ':F' . $endRow);
                $sheet->mergeCells('G' . $startRow . ':G' . $endRow);
                
                // Center align merged cells
                $sheet->getStyle('A' . $startRow . ':G' . $endRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            }
        }
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = $reportPath . '/Backup_All_Orders_' . date('Y-m-d_H-i-s') . '.xlsx';
        $writer->save($filename);
    }

    private function generatePerShopBackup($reportPath)
    {
        $db = \Config\Database::connect();
        $shops = $db->table('users')
                   ->select('users.id, users.shop_name')
                   ->where('role', 'seller')
                   ->where('shop_name IS NOT NULL')
                   ->get()->getResult();

        foreach ($shops as $shop) {
            // Get orders grouped by order_id for this shop
            $orders = $db->table('customer_orders')
                        ->select('customer_orders.id, customer_orders.order_number, customer_orders.customer_name, divisions.nama_divisi, customer_orders.customer_whatsapp, customer_orders.total_amount')
                        ->join('customer_order_items', 'customer_order_items.order_id = customer_orders.id')
                        ->join('product_variants', 'product_variants.id = customer_order_items.product_variant_id')
                        ->join('products', 'products.id = product_variants.product_id')
                        ->join('divisions', 'divisions.id = customer_orders.division_id')
                        ->where('products.user_id', $shop->id)
                        ->groupBy('customer_orders.id')
                        ->orderBy('customer_orders.order_number')
                        ->get()->getResult();
            
            if (empty($orders)) continue;
            
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            $sheet->setCellValue('A1', 'No. Order');
            $sheet->setCellValue('B1', 'Nama');
            $sheet->setCellValue('C1', 'Divisi');
            $sheet->setCellValue('D1', 'WhatsApp');
            $sheet->setCellValue('E1', 'Total Harga');
            $sheet->setCellValue('F1', 'Produk');
            $sheet->setCellValue('G1', 'Catatan');
            $sheet->setCellValue('H1', 'Qty');
            
            $sheet->getStyle('A1:H1')->getFont()->setBold(true);
            
            $row = 2;
            foreach ($orders as $order) {
                // Get order items for this order
                $orderItems = $db->table('customer_order_items')
                                ->select('customer_order_items.*, product_variants.variant_name, products.name as product_name')
                                ->join('product_variants', 'product_variants.id = customer_order_items.product_variant_id')
                                ->join('products', 'products.id = product_variants.product_id')
                                ->where('customer_order_items.order_id', $order->id)
                                ->where('products.user_id', $shop->id)
                                ->get()->getResult();
                
                $startRow = $row;
                foreach ($orderItems as $item) {
                    $sheet->setCellValue('A' . $row, $order->order_number);
                    $sheet->setCellValue('B' . $row, $order->customer_name);
                    $sheet->setCellValue('C' . $row, $order->nama_divisi ?? 'N/A');
                    $sheet->setCellValue('D' . $row, $order->customer_whatsapp);
                    $sheet->setCellValue('E' . $row, 'Rp ' . number_format($order->total_amount, 0, ',', '.'));
                    $sheet->setCellValue('F' . $row, $item->product_name . ' - ' . $item->variant_name);
                    $sheet->setCellValue('G' . $row, $item->note ?? '-');
                    $sheet->setCellValue('H' . $row, $item->quantity);
                    $row++;
                }
                
                // Merge cells for order info if multiple items
                if (count($orderItems) > 1) {
                    $endRow = $row - 1;
                    $sheet->mergeCells('A' . $startRow . ':A' . $endRow);
                    $sheet->mergeCells('B' . $startRow . ':B' . $endRow);
                    $sheet->mergeCells('C' . $startRow . ':C' . $endRow);
                    $sheet->mergeCells('D' . $startRow . ':D' . $endRow);
                    $sheet->mergeCells('E' . $startRow . ':E' . $endRow);
                    
                    // Center align merged cells
                    $sheet->getStyle('A' . $startRow . ':E' . $endRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                }
            }
            
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $filename = $reportPath . '/Backup_' . $shop->shop_name . '_' . date('Y-m-d_H-i-s') . '.xlsx';
            $writer->save($filename);
        }
    }
}