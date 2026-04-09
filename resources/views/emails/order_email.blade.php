<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }} — Tixxy</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        /* Reset */
        body,
        table,
        td,
        p,
        a,
        li,
        blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            outline: none;
            text-decoration: none;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            height: 100% !important;
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* Typography */
        body,
        table,
        td {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        /* Responsive */
        @media only screen and (max-width: 620px) {
            .email-container {
                width: 100% !important;
                max-width: 100% !important;
            }

            .fluid {
                width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
            }

            .stack-column {
                display: block !important;
                width: 100% !important;
            }

            .stack-column-center {
                text-align: center !important;
            }

            .center-on-narrow {
                text-align: center !important;
                display: block !important;
                margin-left: auto !important;
                margin-right: auto !important;
                float: none !important;
            }

            table.center-on-narrow {
                display: inline-block !important;
            }

            .padding-mobile {
                padding-left: 20px !important;
                padding-right: 20px !important;
            }
        }
    </style>
</head>

<body style="margin: 0; padding: 0; background-color: #f3f4f6; width: 100% !important;">

    <!-- Background wrapper -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
        style="background-color: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 40px 10px;">

                <!-- Email Container -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600"
                    class="email-container"
                    style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">

                    <!-- ====== HEADER BANNER ====== -->
                    <tr>
                        <td
                            style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #4338ca 100%); padding: 40px 40px 32px 40px; text-align: center;">
                            <!-- Logo / Brand -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td align="center" style="padding-bottom: 24px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                            align="center" style="margin: 0 auto;">
                                            <tr>
                                                <td style="vertical-align: middle; padding-top: 4px; line-height: 1;">
                                                    <svg style="display: block; width: 32px; height: 32px;" fill="none"
                                                        stroke="#ffffff" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                                        </path>
                                                    </svg>
                                                </td>
                                                <td
                                                    style="vertical-align: middle; padding-left: 8px; font-size: 28px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px; line-height: 1;">
                                                    Tixxy
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <span
                                            style="display: inline-block; background-color: rgba(255,255,255,0.2); color: #e0e7ff; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; padding: 6px 16px; border-radius: 20px;">Order
                                            Confirmation</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-top: 20px;">
                                        <h1
                                            style="margin: 0; font-size: 22px; font-weight: 700; color: #ffffff; line-height: 1.3;">
                                            Your tickets are confirmed! 🎉</h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-top: 8px;">
                                        <p style="margin: 0; font-size: 14px; color: #c7d2fe; line-height: 1.5;">Thank
                                            you for your purchase. Here is your order summary.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ====== ORDER CREDENTIALS ====== -->
                    <tr>
                        <td style="padding: 32px 40px 0 40px;" class="padding-mobile">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                style="background-color: #f9fafb; border-radius: 12px; border: 1px solid #e5e7eb;">
                                <tr>
                                    <td style="padding: 24px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                            width="100%">
                                            <tr>
                                                <td colspan="2"
                                                    style="padding-bottom: 16px; border-bottom: 1px solid #e5e7eb;">
                                                    <span
                                                        style="font-size: 13px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 1px;">Order
                                                        Details</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 16px; width: 50%; vertical-align: top;">
                                                    <p
                                                        style="margin: 0 0 4px 0; font-size: 11px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Order ID</p>
                                                    <p
                                                        style="margin: 0; font-size: 15px; font-weight: 700; color: #111827;">
                                                        #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                                                </td>
                                                <td
                                                    style="padding-top: 16px; width: 50%; vertical-align: top; text-align: right;">
                                                    <p
                                                        style="margin: 0 0 4px 0; font-size: 11px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Order Date</p>
                                                    <p
                                                        style="margin: 0; font-size: 15px; font-weight: 600; color: #111827;">
                                                        {{ $order->created_at->format('d M Y, H:i') }}
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 14px; width: 50%; vertical-align: top;">
                                                    <p
                                                        style="margin: 0 0 4px 0; font-size: 11px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Status</p>
                                                    <p style="margin: 0;">
                                                        <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;
                                                            @if($order->status === 'completed') background-color: #d1fae5; color: #065f46;
                                                            @elseif($order->status === 'pending') background-color: #fef3c7; color: #92400e;
                                                            @else background-color: #fee2e2; color: #991b1b;
                                                            @endif">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </p>
                                                </td>
                                                <td
                                                    style="padding-top: 14px; width: 50%; vertical-align: top; text-align: right;">
                                                    <p
                                                        style="margin: 0 0 4px 0; font-size: 11px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Total Amount</p>
                                                    <p
                                                        style="margin: 0; font-size: 20px; font-weight: 800; color: #4f46e5;">
                                                        Rp {{ number_format($order->amount, 0, ',', '.') }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ====== EVENT INFO ====== -->
                    <tr>
                        <td style="padding: 24px 40px 0 40px;" class="padding-mobile">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                style="background: linear-gradient(135deg, #eef2ff 0%, #faf5ff 100%); border-radius: 12px; border: 1px solid #e0e7ff;">
                                <tr>
                                    <td style="padding: 24px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                            width="100%">
                                            <tr>
                                                <td style="padding-bottom: 16px; border-bottom: 1px solid #c7d2fe;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0"
                                                        border="0">
                                                        <tr>
                                                            <td style="vertical-align: middle; line-height: 1;">
                                                                <svg style="display: block; width: 16px; height: 16px;"
                                                                    fill="none" stroke="#6366f1" viewBox="0 0 24 24"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                    </path>
                                                                </svg>
                                                            </td>
                                                            <td
                                                                style="vertical-align: middle; padding-left: 6px; font-size: 13px; font-weight: 700; color: #6366f1; text-transform: uppercase; letter-spacing: 1px; line-height: 1;">
                                                                Event Information
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 16px;">
                                                    <h2
                                                        style="margin: 0 0 12px 0; font-size: 18px; font-weight: 800; color: #1e1b4b;">
                                                        {{ $order->event->title }}
                                                    </h2>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table role="presentation" cellspacing="0" cellpadding="0"
                                                        border="0" width="100%">
                                                        <tr>
                                                            <td
                                                                style="padding: 6px 0; vertical-align: top; width: 24px;">
                                                                <span style="font-size: 14px;">📅</span>
                                                            </td>
                                                            <td style="padding: 6px 0 6px 8px; vertical-align: top;">
                                                                <p
                                                                    style="margin: 0; font-size: 13px; color: #4b5563; line-height: 1.4;">
                                                                    <strong
                                                                        style="color: #1f2937;">{{ \Carbon\Carbon::parse($order->event->start_time)->format('l, d M Y') }}</strong><br>
                                                                    {{ \Carbon\Carbon::parse($order->event->start_time)->format('h:i A') }}
                                                                    —
                                                                    {{ \Carbon\Carbon::parse($order->event->end_time)->format('h:i A') }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                style="padding: 6px 0; vertical-align: top; width: 24px;">
                                                                <span style="font-size: 14px;">📍</span>
                                                            </td>
                                                            <td style="padding: 6px 0 6px 8px; vertical-align: top;">
                                                                <p style="margin: 0; font-size: 13px; color: #4b5563;">
                                                                    <strong
                                                                        style="color: #1f2937;">{{ $order->event->location }}</strong>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ====== USER / ATTENDEE CREDENTIALS ====== -->
                    <tr>
                        <td style="padding: 24px 40px 0 40px;" class="padding-mobile">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                style="background-color: #f9fafb; border-radius: 12px; border: 1px solid #e5e7eb;">
                                <tr>
                                    <td style="padding: 24px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                            width="100%">
                                            <tr>
                                                <td style="padding-bottom: 16px; border-bottom: 1px solid #e5e7eb;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0"
                                                        border="0">
                                                        <tr>
                                                            <td style="vertical-align: middle; line-height: 1;">
                                                                <svg style="display: block; width: 16px; height: 16px;"
                                                                    fill="none" stroke="#6b7280" viewBox="0 0 24 24"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M19 20v-1.5a4 4 0 00-4-4H9a4 4 0 00-4 4V20">
                                                                    </path>
                                                                    <circle cx="12" cy="7" r="4" stroke-width="2">
                                                                    </circle>
                                                                </svg>
                                                            </td>
                                                            <td
                                                                style="vertical-align: middle; padding-left: 6px; font-size: 13px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; line-height: 1;">
                                                                Attendee Information
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 16px; width: 50%; vertical-align: top;">
                                                    <p
                                                        style="margin: 0 0 4px 0; font-size: 11px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Full Name
                                                    </p>
                                                    <p
                                                        style="margin: 0 0 14px 0; font-size: 15px; font-weight: 700; color: #111827;">
                                                        {{ $order->user->name }}
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;">
                                                    <p
                                                        style="margin: 0 0 4px 0; font-size: 11px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Email Address</p>
                                                    <p
                                                        style="margin: 0; font-size: 15px; font-weight: 600; color: #4f46e5;">
                                                        {{ $order->user->email }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ====== TICKET TYPE BREAKDOWN (Price & Quantity) ====== -->
                    <tr>
                        <td style="padding: 24px 40px 0 40px;" class="padding-mobile">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding-bottom: 16px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="vertical-align: middle; line-height: 1;">
                                                    <svg style="display: block; width: 16px; height: 16px;" fill="none"
                                                        stroke="#6b7280" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                                        </path>
                                                    </svg>
                                                </td>
                                                <td
                                                    style="vertical-align: middle; padding-left: 6px; font-size: 13px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; line-height: 1;">
                                                    Order Summary
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                style="border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden;">
                                <!-- Table Header -->
                                <tr>
                                    <td
                                        style="background-color: #f9fafb; padding: 12px 16px; border-bottom: 2px solid #e5e7eb;">
                                        <span
                                            style="font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Ticket
                                            Type</span>
                                    </td>
                                    <td align="center"
                                        style="background-color: #f9fafb; padding: 12px 16px; border-bottom: 2px solid #e5e7eb;">
                                        <span
                                            style="font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Qty</span>
                                    </td>
                                    <td align="right"
                                        style="background-color: #f9fafb; padding: 12px 16px; border-bottom: 2px solid #e5e7eb;">
                                        <span
                                            style="font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Price</span>
                                    </td>
                                    <td align="right"
                                        style="background-color: #f9fafb; padding: 12px 16px; border-bottom: 2px solid #e5e7eb;">
                                        <span
                                            style="font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Subtotal</span>
                                    </td>
                                </tr>

                                <!-- Table Rows — grouped by event_ticket_type_id -->
                                @php
                                    $grouped = $order->orderDetails->groupBy('event_ticket_type_id');
                                @endphp

                                @foreach($grouped as $eventTicketTypeId => $details)
                                    @php
                                        $firstDetail = $details->first();
                                        $ticketTypeName = $firstDetail->eventTicketType->ticketType->name ?? 'Unknown';
                                        $price = $firstDetail->eventTicketType->price ?? 0;
                                        $quantity = $details->count();
                                        $subtotal = $price * $quantity;
                                    @endphp
                                    <tr>
                                        <td style="padding: 14px 16px; border-bottom: 1px solid #f3f4f6;">
                                            <p style="margin: 0; font-size: 14px; font-weight: 600; color: #111827;">
                                                {{ $ticketTypeName }}
                                            </p>
                                        </td>
                                        <td align="center" style="padding: 14px 16px; border-bottom: 1px solid #f3f4f6;">
                                            <span
                                                style="display: inline-block; background-color: #eef2ff; color: #4338ca; font-size: 13px; font-weight: 700; padding: 2px 10px; border-radius: 12px;">{{ $quantity }}</span>
                                        </td>
                                        <td align="right" style="padding: 14px 16px; border-bottom: 1px solid #f3f4f6;">
                                            <p style="margin: 0; font-size: 13px; color: #6b7280;">Rp
                                                {{ number_format($price, 0, ',', '.') }}
                                            </p>
                                        </td>
                                        <td align="right" style="padding: 14px 16px; border-bottom: 1px solid #f3f4f6;">
                                            <p style="margin: 0; font-size: 14px; font-weight: 700; color: #111827;">Rp
                                                {{ number_format($subtotal, 0, ',', '.') }}
                                            </p>
                                        </td>
                                    </tr>
                                @endforeach

                                <!-- Total Row -->
                                <tr>
                                    <td colspan="3" align="right"
                                        style="padding: 16px 16px; background-color: #f9fafb;">
                                        <span style="font-size: 14px; font-weight: 700; color: #111827;">Total</span>
                                    </td>
                                    <td align="right" style="padding: 16px 16px; background-color: #f9fafb;">
                                        <span style="font-size: 18px; font-weight: 800; color: #4f46e5;">Rp
                                            {{ number_format($order->amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ====== QR CODES — Grouped by Ticket Type ====== -->
                    <tr>
                        <td style="padding: 32px 40px 0 40px;" class="padding-mobile">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding-bottom: 8px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="vertical-align: middle; line-height: 1;">
                                                    <svg style="display: block; width: 16px; height: 16px;" fill="none"
                                                        stroke="#6b7280" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                                        </path>
                                                    </svg>
                                                </td>
                                                <td
                                                    style="vertical-align: middle; padding-left: 6px; font-size: 13px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; line-height: 1;">
                                                    Your E-Tickets
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 20px;">
                                        <p style="margin: 0; font-size: 13px; color: #9ca3af; line-height: 1.4;">Present
                                            each QR code at the event entrance for scanning.</p>
                                    </td>
                                </tr>
                            </table>

                            @foreach($grouped as $eventTicketTypeId => $details)
                                @php
                                    $firstDetail = $details->first();
                                    $ticketTypeName = $firstDetail->eventTicketType->ticketType->name ?? 'Unknown';
                                @endphp

                                <!-- Ticket Type Group Header -->
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                    style="margin-bottom: 8px;">
                                    <tr>
                                        <td
                                            style="padding: 10px 16px; background: linear-gradient(135deg, #4f46e5, #7c3aed); border-radius: 10px 10px 0 0;">
                                            <span
                                                style="font-size: 13px; font-weight: 700; color: #ffffff; text-transform: uppercase; letter-spacing: 0.5px;">{{ $ticketTypeName }}
                                            </span>
                                            <span style="font-size: 11px; color: #c7d2fe; margin-left: 8px;">—
                                                {{ $details->count() }}
                                                {{ Str::plural('ticket', $details->count()) }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>

                                <!-- Individual QR Code Cards -->
                                @foreach($details as $index => $detail)
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                        style="background-color: #ffffff; border: 1px solid #e5e7eb; {{ $loop->last ? 'border-radius: 0 0 12px 12px; margin-bottom: 24px;' : 'border-bottom: none;' }}">
                                        <tr>
                                            <td style="padding: 20px;" align="center">
                                                <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                                    <tr>
                                                        <td align="center" style="padding-bottom: 12px;">
                                                            <!-- Ticket number badge -->
                                                            <span
                                                                style="display: inline-block; font-size: 11px; font-weight: 700; color: #6366f1; background-color: #eef2ff; padding: 4px 12px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px;">
                                                                Ticket #{{ str_pad($detail->ticket->id, 6, '0', STR_PAD_LEFT) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center"
                                                            style="padding: 8px; background-color: #ffffff; border: 2px solid #e5e7eb; border-radius: 12px;">
                                                            <!-- QR Code Image via free API -->
                                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($detail->ticket->qr_code_hash) }}&margin=4"
                                                                alt="QR Code for Ticket #{{ str_pad($detail->ticket->id, 6, '0', STR_PAD_LEFT) }}"
                                                                width="180" height="180"
                                                                style="display: block; width: 180px; height: 180px;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="padding-top: 10px;">
                                                            <p
                                                                style="margin: 0; font-size: 10px; font-family: 'Courier New', Courier, monospace; color: #9ca3af; letter-spacing: 0.5px; word-break: break-all; max-width: 220px;">
                                                                {{ $detail->ticket->qr_code_hash }}
                                                            </p>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endforeach
                        </td>
                    </tr>

                    <!-- ====== IMPORTANT NOTICE ====== -->
                    <tr>
                        <td style="padding: 24px 40px 0 40px;" class="padding-mobile">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                style="background-color: #fffbeb; border-radius: 12px; border: 1px solid #fde68a;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                            width="100%">
                                            <tr>
                                                <td style="width: 24px; vertical-align: top;">
                                                    <span style="font-size: 16px;">⚠️</span>
                                                </td>
                                                <td style="padding-left: 10px; vertical-align: top;">
                                                    <p
                                                        style="margin: 0 0 4px 0; font-size: 13px; font-weight: 700; color: #92400e;">
                                                        Important
                                                    </p>
                                                    <p
                                                        style="margin: 0; font-size: 12px; color: #a16207; line-height: 1.5;">
                                                        Each QR code can only be scanned once. Please do not share your
                                                        tickets with others. Have your QR code ready upon arrival at the
                                                        venue.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ====== FOOTER ====== -->
                    <tr>
                        <td style="padding: 32px 40px 40px 40px;" class="padding-mobile">
                            <!-- Divider -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                style="margin-bottom: 24px;">
                                <tr>
                                    <td style="border-top: 1px solid #e5e7eb;"></td>
                                </tr>
                            </table>

                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td align="center" style="padding-bottom: 8px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                            align="center" style="margin: 0 auto;">
                                            <tr>
                                                <td style="vertical-align: middle; padding-top: 2px; line-height: 1;">
                                                    <svg style="display: block; width: 20px; height: 20px;" fill="none"
                                                        stroke="#6366f1" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                                        </path>
                                                    </svg>
                                                </td>
                                                <td
                                                    style="vertical-align: middle; padding-left: 5px; font-size: 14px; font-weight: 700; color: #6366f1; line-height: 1;">
                                                    Tixxy
                                                </td>
                                            </tr>
                                        </table>
                                        <p style="margin: 0 0 4px 0; font-size: 12px; color: #9ca3af;">Your Ticket to
                                            Unforgettable Experiences
                                        </p>
                                        <p style="margin: 0; font-size: 11px; color: #d1d5db; line-height: 1.5;">
                                            This is an automated email. Please do not reply to this message.<br>
                                            &copy; {{ date('Y') }} Tixxy. All rights reserved.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
                <!-- /Email Container -->

            </td>
        </tr>
    </table>
    <!-- /Background wrapper -->

</body>

</html>