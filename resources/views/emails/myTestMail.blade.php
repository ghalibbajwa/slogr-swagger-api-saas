<!DOCTYPE html>
<html>

<head>
    <title>slogr.io</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 200px;
            height: auto;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .footer {
            text-align: center;
            color: #999;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="https://slogr.io/wp-content/uploads/2023/06/Group-2420.png" alt="Logo">
        </div>
        <h1>{{ $details['title'] }}</h1>
        <p>{{ $details['body'] }}</p>
        
        @if(isset($details['arrayData']))
        <p>The array data as a string:</p>
        <p>{{ implode(", ", $details['arrayData']) }}</p>
        @endif

        <p class="footer">Thank you</p>
    </div>
</body>

</html>
