<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balasan dari Matla University</title>
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .card {
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
        }
        .header {
            background-color: #047857; /* Emerald 700 */
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #0f172a;
        }
        .message-body {
            font-size: 16px;
            color: #334155;
            white-space: pre-wrap;
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #047857;
            margin-bottom: 30px;
        }
        .footer {
            padding: 20px 30px;
            text-align: center;
            background-color: #f8fafc;
            border-top: 1px solid #f1f5f9;
            font-size: 14px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>Matla University</h1>
            </div>
            
            <div class="content">
                <div class="greeting">
                    Halo {{ $originalSenderName }},
                </div>
                
                <p>Terima kasih telah menghubungi kami. Berikut adalah balasan dari tim Matla University mengenai pesan Anda:</p>
                
                <div class="message-body">{{ $replyMessage }}</div>
                
                <p>Jika ada pertanyaan lebih lanjut, jangan ragu untuk membalas email ini.</p>
                
                <p style="margin-top: 30px; font-weight: 600;">
                    Salam hangat,<br>
                    Tim Matla University
                </p>
            </div>
            
            <div class="footer">
                &copy; {{ date('Y') }} Matla University. Hak cipta dilindungi undang-undang.<br>
                Email ini dikirim secara otomatis melalui sistem.
            </div>
        </div>
    </div>
</body>
</html>
