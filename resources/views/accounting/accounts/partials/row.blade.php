@php
    $indent = str_repeat('— ', $depth);
@endphp
<tr>
    <td>{{ $account->code }}</td>
    <td>{{ $indent }}{{ $account->name }}</td>
    <td>{{ ucfirst($account->type) }}</td>
    <td class="text-end">{{ number_format($account->opening_balance, 2) }}</td>
    <td class="text-end">
        @can('accounts.update')
        <a href="{{ route('accounts.edit', $account) }}" class="btn btn-sm btn-outline-primary">Edit</a>
        @endcan
        @can('accounts.delete')
        <form action="{{ route('accounts.destroy', $account) }}" method="post" class="d-inline">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this account?')">Delete</button>
        </form>
        @endcan
    </td>
</tr>
@foreach($account->children as $child)
    @include('accounting.accounts.partials.row', ['account' => $child, 'depth' => $depth + 1])
@endforeach

