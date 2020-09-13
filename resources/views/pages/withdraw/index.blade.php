@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Withdraw List</div>

                <div class="card-body">
                    My Saldo : {{ number_format($store->saldo) }} <br>
                    <p style="margin-top:20px">
                        <a href="{{ route('withdraw.create') }}" class="btn btn-primary">Tarik</a>
                    </p>

                    <table class="table">
                        <thead>
                            <th>No</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Time Served</th>
                            <th>Receipt</th>
                            <th>Fee</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($withdraws as $idx => $withdraw)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ $withdraw->amount }}</td>
                                <td>{{ $withdraw->status }}</td>
                                <td>{{ $withdraw->time_served }}</td>
                                <td>
                                    @if ($withdraw->receipt)
                                        <a href="{{$withdraw->receipt}}" target="_blank">Link Receipt</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $withdraw->fee }}</td>
                                <td>
                                    <button class="btn btn-success btn-small" onclick="return refreshStatus({{ $withdraw->transaction_id }})">
                                        Refresh Status
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function refreshStatus(id) {
        var xhReq = new XMLHttpRequest();
        xhReq.open("GET", "/disburse/"+ id);
        xhReq.send(null);

        // reload then
        location.reload();
    }
</script>
@endsection
