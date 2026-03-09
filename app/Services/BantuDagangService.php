<?php

namespace App\Services;

class BantuDagangService
{
    /**
     * Process an Order ID
     * Mock business logic for submitting an order tracking request
     * 
     * @param string $orderId
     * @return array
     */
    public function processOrderID(string $orderId)
    {
        // Simulate a delay
        sleep(1);

        // Mock response data
        return [
            'order_id' => $orderId,
            'status' => 'Pending Review',
            'submitted_at' => now()->toDateTimeString(),
            'notes' => 'Simulated successful order submission'
        ];
    }

    /**
     * Get Chat History for an Order ID
     * Mock business logic for retrieving Chat History
     * 
     * @param string $orderId
     * @return array
     */
    public function getChatHistory(string $orderId)
    {
        // Simulate a delay
        sleep(1);

        // Mock chat history
        return [
            [
                'sender' => 'Customer',
                'message' => 'Hi, I need help with my order ' . $orderId,
                'timestamp' => now()->subMinutes(10)->toDateTimeString()
            ],
            [
                'sender' => 'Agent',
                'message' => 'Sure, I can help you with that. Can you confirm the shipping address?',
                'timestamp' => now()->subMinutes(5)->toDateTimeString()
            ]
        ];
    }
}
