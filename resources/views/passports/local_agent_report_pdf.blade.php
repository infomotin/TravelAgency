<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Local Agent Commission Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .bg-light { background-color: #f9f9f9; }
        .section-header { background-color: #e9ecef; font-weight: bold; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Local Agent Commission Report</h2>
    <p style="text-align: center;">Period: {{ $fromDate }} to {{ $toDate }}</p>

    @if($reportData->isEmpty())
        <p>No data found for the selected period.</p>
    @else
        @foreach($reportData as $agentId => $agent)
            <table>
                <thead>
                    <tr class="section-header">
                        <th colspan="4">Agent: {{ $agent['name'] }}</th>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <th>Month</th>
                        <th class="text-end">Count</th>
                        <th class="text-end">Commission</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agent['countries'] as $countryId => $country)
                        @foreach($country['months'] as $month => $data)
                            <tr>
                                <td>{{ $country['name'] }}</td>
                                <td>{{ $month }}</td>
                                <td class="text-end">{{ $data['count'] }}</td>
                                <td class="text-end">{{ number_format($data['total_commission'], 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="bg-light fw-bold">
                            <td colspan="3" class="text-end">Total for {{ $country['name'] }}:</td>
                            <td class="text-end">{{ number_format($country['total_commission'], 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="section-header">
                        <td colspan="3" class="text-end">Total for {{ $agent['name'] }}:</td>
                        <td class="text-end">{{ number_format($agent['total_commission'], 2) }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    @endif
</body>
</html>
