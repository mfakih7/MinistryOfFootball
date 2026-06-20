<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case WhatsappContacted = 'whatsapp_contacted';
    case Confirmed = 'confirmed';
    case Preparing = 'preparing';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
}
