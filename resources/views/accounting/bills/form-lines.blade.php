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
<div class="d-flex justify-content-between align-items-center mb-2">
    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addBillLine()">Add Line</button>
    <div class="fw-semibold">
        Total Amount:
        <span id="bill-total-amount">0.00</span>
    </div>
</div>

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
        attachBillLineEvents(row);
        billLineIndex++;
        recalcBillTotal();
    }
    function removeBillLine(button) {
        const row = button.closest('tr');
        row.remove();
        recalcBillTotal();
    }
    function attachBillLineEvents(row) {
        const qtyInput = row.querySelector('input[name*="[quantity]"]');
        const priceInput = row.querySelector('input[name*="[unit_price]"]');
        const amountInput = row.querySelector('input[name*="[amount]"]');
        if (qtyInput && priceInput) {
            qtyInput.addEventListener('input', function () {
                recalcBillLine(row);
            });
            priceInput.addEventListener('input', function () {
                recalcBillLine(row);
            });
        }
        if (amountInput) {
            amountInput.addEventListener('input', function () {
                recalcBillTotal();
            });
        }
    }
    function recalcBillLine(row) {
        const qtyInput = row.querySelector('input[name*="[quantity]"]');
        const priceInput = row.querySelector('input[name*="[unit_price]"]');
        const amountInput = row.querySelector('input[name*="[amount]"]');
        const qty = qtyInput ? parseFloat(qtyInput.value) || 0 : 0;
        const price = priceInput ? parseFloat(priceInput.value) || 0 : 0;
        const amount = qty * price;
        if (amountInput) {
            amountInput.value = amount ? amount.toFixed(2) : '';
        }
        recalcBillTotal();
    }
    function recalcBillTotal() {
        const amountInputs = document.querySelectorAll('#bill-lines-body input[name*="[amount]"]');
        let total = 0;
        amountInputs.forEach(function (input) {
            const value = parseFloat(input.value);
            if (!isNaN(value)) {
                total += value;
            }
        });
        const totalEl = document.getElementById('bill-total-amount');
        if (totalEl) {
            totalEl.textContent = total.toFixed(2);
        }
    }
    document.addEventListener('DOMContentLoaded', function () {
        const rows = document.querySelectorAll('#bill-lines-body tr');
        rows.forEach(function (row) {
            attachBillLineEvents(row);
            recalcBillLine(row);
        });
        recalcBillTotal();
    });
</script>
