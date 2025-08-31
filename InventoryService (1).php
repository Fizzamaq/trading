<?php

namespace App\Services;

use App\Models\InventoryLot;
use App\Models\SalesInvoiceItem;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Apply FIFO method to allocate sold quantity from inventory lots
     */
    public function allocateInventoryFifo($productName, $quantityToSell, $sellingPrice)
    {
        $allocations = [];
        $remainingQuantity = $quantityToSell;
        $totalCost = 0;

        $availableLots = InventoryLot::where('product_name', $productName)
            ->where('remaining_quantity', '>', 0)
            ->orderBy('purchase_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        if ($availableLots->sum('remaining_quantity') < $quantityToSell) {
            throw new \Exception('Insufficient inventory for product: ' . $productName);
        }

        foreach ($availableLots as $lot) {
            if ($remainingQuantity <= 0) {
                break;
            }

            $quantityFromThisLot = min($remainingQuantity, $lot->remaining_quantity);
            $costFromThisLot = $quantityFromThisLot * $lot->unit_cost;
            
            $allocations[] = [
                'inventory_lot_id' => $lot->id,
                'quantity_sold' => $quantityFromThisLot,
                'unit_selling_price' => $sellingPrice,
                'unit_cost_price' => $lot->unit_cost,
                'line_total' => $quantityFromThisLot * $sellingPrice,
                'line_cost' => $costFromThisLot,
                'line_profit' => ($quantityFromThisLot * $sellingPrice) - $costFromThisLot,
            ];

            $totalCost += $costFromThisLot;
            $remainingQuantity -= $quantityFromThisLot;
        }

        return [
            'allocations' => $allocations,
            'total_cost' => $totalCost,
            'total_selling_amount' => $quantityToSell * $sellingPrice,
            'total_profit' => ($quantityToSell * $sellingPrice) - $totalCost,
        ];
    }

    /**
     * Update inventory after sale
     */
    public function updateInventoryAfterSale($allocations)
    {
        DB::beginTransaction();

        try {
            foreach ($allocations as $allocation) {
                $lot = InventoryLot::find($allocation['inventory_lot_id']);
                $lot->remaining_quantity -= $allocation['quantity_sold'];
                $lot->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get inventory summary
     */
    public function getInventorySummary()
    {
        return InventoryLot::selectRaw('
            product_name,
            SUM(remaining_quantity) as total_quantity,
            SUM(remaining_quantity * unit_cost) as total_value,
            AVG(unit_cost) as avg_cost,
            COUNT(*) as lot_count
        ')
        ->where('remaining_quantity', '>', 0)
        ->groupBy('product_name')
        ->orderBy('product_name')
        ->get();
    }

    /**
     * Calculate lot-level profitability (Owner only)
     */
    public function getLotProfitability($lotId)
    {
        $lot = InventoryLot::findOrFail($lotId);
        
        $salesItems = SalesInvoiceItem::where('inventory_lot_id', $lotId)->get();
        
        $totalSold = $salesItems->sum('quantity_sold');
        $totalRevenue = $salesItems->sum('line_total');
        $totalCost = $salesItems->sum('line_cost');
        $totalProfit = $totalRevenue - $totalCost;
        
        return [
            'lot' => $lot,
            'quantity_sold' => $totalSold,
            'remaining_quantity' => $lot->remaining_quantity,
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'total_profit' => $totalProfit,
            'profit_margin' => $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0,
        ];
    }
}
