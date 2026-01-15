<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Passport Report</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 4px 6px; }
        th { background-color: #f3f4f6; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
<h1>Passport Report</h1>
<p>
    @if(!empty($filters['from_date']) || !empty($filters['to_date']))
        Date: {{ $filters['from_date'] ?? '...' }} to {{ $filters['to_date'] ?? '...' }}<br>
    @endif
    @if(!empty($filters['purpose']))
        Purpose: {{ $purposes[$filters['purpose']] ?? $filters['purpose'] }}<br>
    @endif
    @if(!empty($filters['local_agent_name']))
        Local agent: {{ $filters['local_agent_name'] }}<br>
    @endif
    @if(!empty($filters['country_id']))
        Country ID: {{ $filters['country_id'] }}<br>
    @endif
</p>
<table>
    <thead>
    <tr>
        <th>Country</th>
        <th>Local agent</th>
        <th>Purpose</th>
        <th class="text-right">Passports</th>
        <th class="text-right">Entry charge</th>
        <th class="text-right">Agent commission</th>
    </tr>
    </thead>
    <tbody>
    @php
        $sumPassports = 0;
        $sumEntry = 0;
        $sumCommission = 0;
    @endphp
    @forelse($rows as $row)
        @php
            $sumPassports += $row->total_passports;
            $sumEntry += $row->total_entry_charge;
            $sumCommission += $row->total_agent_commission;
            $purposeKey = $row->purpose ?? '';
        @endphp
        <tr>
            <td>{{ $row->country_name ?: 'N/A' }}</td>
            <td>{{ $row->local_agent_name ?: 'N/A' }}</td>
            <td>{{ $purposes[$purposeKey] ?? ($purposeKey ?: 'N/A') }}</td>
            <td class="text-right">{{ $row->total_passports }}</td>
            <td class="text-right">{{ number_format($row->total_entry_charge, 2) }}</td>
            <td class="text-right">{{ number_format($row->total_agent_commission, 2) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="6">No data.</td>
        </tr>
    @endforelse
    </tbody>
    @if($rows->count())
        <tfoot>
        <tr>
            <th colspan="3" class="text-right">Total</th>
            <th class="text-right">{{ $sumPassports }}</th>
            <th class="text-right">{{ number_format($sumEntry, 2) }}</th>
            <th class="text-right">{{ number_format($sumCommission, 2) }}</th>
        </tr>
        </tfoot>
    @endif
</table>
</body>
</html>

