<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>{{ __('messages.feedback_form.title') }}</title>
    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        p {
            font-size: 14px;
            line-height: 1.6;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>{{ __('messages.feedback_form.title') }}</h1>
    <p><span class="label">{{ __('messages.feedback_form.name_label') }}:</span> {{ htmlspecialchars($name) }}</p>
    <p><span class="label">{{ __('messages.feedback_form.phone_label') }}:</span> {{ htmlspecialchars($phone) }}</p>
    <p><span class="label">{{ __('messages.feedback_form.comment_label') }}:</span> {{ htmlspecialchars($comment) }}</p>
</div>
</body>
</html>
