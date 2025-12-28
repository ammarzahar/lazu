<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LAZU Weekly Marketing Report</title>
    </head>
    <body style="font-family: Arial, sans-serif; color: #1f2937; background: #f9fafb; padding: 24px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; padding: 24px;">
            <tr>
                <td>
                    <h2 style="margin: 0 0 12px;">LAZU Weekly Marketing Report</h2>
                    <p style="margin: 0 0 16px; color: #6b7280;">Here's the weekly summary and priorities.</p>
                    <h3 style="margin: 16px 0 8px;">What Worked</h3>
                    <ul style="margin: 0 0 16px; padding-left: 18px;">
                        @foreach ($report['worked'] ?? [] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                    <h3 style="margin: 16px 0 8px;">What Didn't Work</h3>
                    <ul style="margin: 0 0 16px; padding-left: 18px;">
                        @foreach ($report['didnt_work'] ?? [] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                    <h3 style="margin: 16px 0 8px;">Focus Next Week</h3>
                    <p style="margin: 0 0 16px;">{{ $report['focus'] ?? '' }}</p>
                    <h3 style="margin: 16px 0 8px;">Top 3 Actions</h3>
                    <ol style="margin: 0 0 8px; padding-left: 18px;">
                        @foreach ($report['actions'] ?? [] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ol>
                </td>
            </tr>
        </table>
    </body>
</html>
