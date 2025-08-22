{{-- filepath: /home/ubuntu/learn_words/learn_words/resources/views/collections/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="card-container">
    <div class="card collection-card" data-bs-toggle="modal" data-bs-target="#createCollectionModal">
        <div class="collection-icon">
            <i class="bx bx-plus-circle" style="font-size:2.5rem;color:#1976d2;"></i>
        </div>
        <h2>Create Collection</h2>
        <p>Add a new collection to organize your words.</p>
    </div>
    <div class="card collection-card" onclick="location.href='{{ route('wordCollection.edit') }}'">
        <div class="collection-icon">
            <i class="bx bx-edit" style="font-size:2.5rem;color:#1976d2;"></i>
        </div>
        <h2>Edit Collections and Words</h2>
        <p>Modify your existing collections and words.</p>
    </div>
    <div class="card collection-card" onclick="location.href='{{ route('wordCollection.edit') }}'">
        <div class="collection-icon">
            <i class="bx bx-trash" style="font-size:2.5rem;color:#d32f2f;"></i>
        </div>
        <h2>Delete Collections</h2>
        <p>Remove collections you no longer need.</p>
    </div>
    <div class="card collection-card" onclick="location.href='{{ route('collections.stats') }}'">
        <div class="collection-icon">
            <i class="bx bx-bar-chart-alt-2" style="font-size:2.5rem;color:#388e3c;"></i>
        </div>
        <h2>Collections Statistics</h2>
        <p>View statistics about your collections and words.</p>
    </div>
</div>

@include('components.wordcollection.create-modal')
@endsection