@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#createCard" data-whatever="@mdo">Додати картку</button>
            <div class="card mt-4">
                <div class="card-header">Список карток</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success mb-3" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row">
                        @foreach($cards as $card)
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $card['name'] }}</h5>
                                        <p class="card-text">{{ $card['bank_name'] }}</p>
                                        <a href="{{ $card['id'] }}" class="btn btn-primary">Інформація по картці</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createCard" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Нова картка</h5>
                <button type="button" class="close border-0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('card.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-form-label">Назва:</label>
                        <input name="name" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Назва банку:</label>
                        <input name="bank_name" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Номер картки:</label>
                        <input name="number" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Баланс картки:</label>
                        <input name="balance" type="number" class="form-control">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрити</button>
                    <button type="submit" class="btn btn-success">Створити</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
