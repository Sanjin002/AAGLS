@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Sent Parcels</h1>
    
    @if($sentParcels->isEmpty())
        <p>No sent parcels found.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Client Reference</th>
                    <th>Delivery Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sentParcels as $parcel)
                    <tr>
                        <td>{{ $parcel->ClientReference }}</td>
                        <td>{{ $parcel->DeliveryName }}</td>
                        <td>{{ ucfirst($parcel->status) }}</td>
                        <td>
                            <a href="{{ route('parcels.show', $parcel) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('parcels.print', $parcel) }}" class="btn btn-sm btn-success">Print Label</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    
    <a href="{{ route('parcels.index') }}" class="btn btn-primary mt-3">Back to All Parcels</a>
</div>
@endsection