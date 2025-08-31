<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function createNotification($userId, $title, $message, $type = 'info', $data = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => $data,
        ]);
    }

    public function notifyOwner($title, $message, $type = 'info', $data = null)
    {
        $owner = User::where('role', 'owner')->first();

        if ($owner) {
            return $this->createNotification($owner->id, $title, $message, $type, $data);
        }
    }

    public function notifyProfitWithdrawalRequest($investorRequest)
    {
        $title = 'New Profit Withdrawal Request';
        $message = $investorRequest->investor->user->name . ' has requested to withdraw PKR ' . number_format($investorRequest->amount, 2);

        return $this->notifyOwner($title, $message, 'info', [
            'type' => 'investor_request',
            'request_id' => $investorRequest->id,
            'request_type' => $investorRequest->request_type,
        ]);
    }

    public function notifyProfitDistributionCompleted($monthlyProfit)
    {
        $title = 'Monthly Profit Distribution Completed';
        $message = 'Profit distribution for ' . $monthlyProfit->month_name . ' has been completed. Net profit: PKR ' . number_format($monthlyProfit->net_profit, 2);

        $this->notifyOwner($title, $message, 'success', [
            'type' => 'profit_distribution',
            'monthly_profit_id' => $monthlyProfit->id,
        ]);

        $investors = User::where('role', 'investor')->where('status', 'active')->get();

        foreach ($investors as $investor) {
            $distribution = $monthlyProfit->investorDistributions()
                ->whereHas('investor', function ($query) use ($investor) {
                    $query->where('user_id', $investor->id);
                })
                ->first();

            if ($distribution) {
                $investorMessage = 'Your profit share for ' . $monthlyProfit->month_name . ' is PKR ' . number_format($distribution->investor_portion, 2);

                $this->createNotification(
                    $investor->id,
                    $title,
                    $investorMessage,
                    'success',
                    [
                        'type' => 'profit_distribution',
                        'distribution_id' => $distribution->id,
                        'amount' => $distribution->investor_portion,
                    ]
                );
            }
        }
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);

        if ($notification && !$notification->is_read) {
            $notification->markAsRead();
        }

        return $notification;
    }

    public function getUserUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)->unread()->count();
    }
}
