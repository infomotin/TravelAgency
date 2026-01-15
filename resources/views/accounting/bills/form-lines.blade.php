<table class="table table-sm align-middle">
    <thead class="table-light">
    <tr>
        <th style="width: 35%;">Account</th>
        <th>Description</th>
        <th style="width: 12%;">Qty</th>
        <th style="width: 15%;">Unit Price</th>
        <th style="width: 15%;">Amount</th>
        <th style="width: 40px;"></th>
    </tr>
    </thead>
    <tbody id="bill-lines-body">
    @php
        $oldLines = old('lines', isset($bill) ? $bill->lines->toArray() : [['account_id' => '', 'description' => '', 'quantity' => 1, 'unit_price' => '', 'amount' => '']]);
    @endphp
    @foreach($oldLines as $index => $line)
        <tr>
            <td>
                <select name="lines[{{ $index }}][account_id]" class="form-select form-select-sm">
                    <option value="">Select account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}"
                            @if(isset($line['account_id']) && $line['account_id'] == $account->id) selected @endif>
                            {{ $account->code }} - {{ $account->name }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" name="lines[{{ $index }}][description]" value="{{ $line['description'] ?? '' }}" class="form-control form-control-sm">
            </td>
            <td>
                <input type="number" step="0.01" name="lines[{{ $index }}][quantity]" value="{{ $line['quantity'] ?? 1 }}" class="form-control form-control-sm">
            </td>
            <td>
                <input type="number" step="0.01" name="lines[{{ $index }}][unit_price]" value="{{ $line['unit_price'] ?? '' }}" class="form-control form-control-sm">
            </td>
            <td>
                <input type="number" step="0.01" name="lines[{{ $index }}][amount]" value="{{ $line['amount'] ?? '' }}" class="form-control form-control-sm">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeBillLine(this)">×</button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<button type="button" class="btn btn-sm btn-outline-secondary" onclick="addBillLine()">Add Line</button>

@error('lines')
<div class="text-danger small mt-2">{{ $message }}</div>
@enderror

<script>
    let billLineIndex = {{ count($oldLines) }};
    function addBillLine() {
        const tbody = document.getElementById('bill-lines-body');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <select name="lines[${billLineIndex}][account_id]" class="form-select form-select-sm">
                    <option value="">Select account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" name="lines[${billLineIndex}][description]" class="form-control form-control-sm">
            </td>
            <td>
                <input type="number" step="0.01" name="lines[${billLineIndex}][quantity]" value="1" class="form-control form-control-sm">
            </td>
            <td>
                <input type="number" step="0.01" name="lines[${billLineIndex}][unit_price]" class="form-control form-control-sm">
            </td>
            <td>
                <input type="number" step="0.01" name="lines[${billLineIndex}][amount]" class="form-control form-control-sm">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeBillLine(this)">×</button>
            </td>
        `;
        tbody.appendChild(row);
        billLineIndex++;
    }
    function removeBillLine(button) {
        const row = button.closest('tr');
        row.remove();
    }
</script>

