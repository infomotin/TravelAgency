<table class="table table-sm align-middle">
    <thead class="table-light">
    <tr>
        <th style="width: 30%;">Account</th>
        <th style="width: 15%;">Debit</th>
        <th style="width: 15%;">Credit</th>
        <th>Description</th>
        <th style="width: 40px;"></th>
    </tr>
    </thead>
    <tbody id="transaction-lines-body">
    @php
        $oldLines = old('lines', isset($transaction) ? $transaction->lines->toArray() : [['account_id' => '', 'debit' => '', 'credit' => '', 'description' => '']]);
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
                <input type="number" step="0.01" name="lines[{{ $index }}][debit]" value="{{ $line['debit'] ?? '' }}" class="form-control form-control-sm">
            </td>
            <td>
                <input type="number" step="0.01" name="lines[{{ $index }}][credit]" value="{{ $line['credit'] ?? '' }}" class="form-control form-control-sm">
            </td>
            <td>
                <input type="text" name="lines[{{ $index }}][description]" value="{{ $line['description'] ?? '' }}" class="form-control form-control-sm">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLine(this)">×</button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<button type="button" class="btn btn-sm btn-outline-secondary" onclick="addLine()">Add Line</button>

@error('lines')
<div class="text-danger small mt-2">{{ $message }}</div>
@enderror

<script>
    let lineIndex = {{ count($oldLines) }};
    function addLine() {
        const tbody = document.getElementById('transaction-lines-body');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <select name="lines[${lineIndex}][account_id]" class="form-select form-select-sm">
                    <option value="">Select account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" step="0.01" name="lines[${lineIndex}][debit]" class="form-control form-control-sm">
            </td>
            <td>
                <input type="number" step="0.01" name="lines[${lineIndex}][credit]" class="form-control form-control-sm">
            </td>
            <td>
                <input type="text" name="lines[${lineIndex}][description]" class="form-control form-control-sm">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLine(this)">×</button>
            </td>
        `;
        tbody.appendChild(row);
        lineIndex++;
    }
    function removeLine(button) {
        const row = button.closest('tr');
        row.remove();
    }
</script>

