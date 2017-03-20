@extends('layout.base')

@section('head')
    <title>Roman numerals converter</title>
@endsection

@section('content')
    <div class="wrapper">
        <section class="section section--header">
            <h1 class="text-center">Stores REST API</h1>
        </section>

        <section class="section section--api">
            <h3 class="section-title">Reference</h3>

            <ul class="list-unstyled">
                <li><a href="{{ url('stores')}}">/stores</a> - return all stores</li>
                <li><a href="{{ url('stores/storenumber')}}">/stores/storenumber</a> - return random store</li>
                <li><a href="{{ url('stores/storenumber', ['id' => 810])}}">/stores/storenumber/810</a> - return store
                    by ID
                </li>
            </ul>
        </section>

        <section class="section section--database">
            <h3 class="section-title">Database</h3>
            <p>
                Currently the database contains {{ $databaseCount  }} stores
            </p>
            <form action="{{ url('stores/clear')}}" method="POST"
                  class="form">
                {{ csrf_field() }}
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn--submit btn-lg center-block">
                        Clear database
                    </button>
                </div>
            </form>
        </section>

        @include ('partials.errors')

        <section class="section section--forms">
            <div>
                <h3 class="section-title">XML Uploader</h3>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#home" aria-controls="home" role="tab" data-toggle="tab">
                            Server
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">
                            Url
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">
                            File
                        </a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="home">
                        <form action="{{ url('stores/download')}}" method="POST"
                              class="form form--server">
                            {{ csrf_field() }}

                            <input type="hidden" name="url" id="task-name" class="form-control"
                                   value="{{ $defaultServerXMLUrl }}">

                            <p>
                                Upload the latest stores downloaded from official server.
                            </p>

                            <div class="form-group">
                                @include ('partials._upload')
                            </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="profile">
                        <form id="form-url" action="{{ url('stores.url')}}" method="POST"
                              class="form form--url">
                            {{ csrf_field() }}

                            <p>
                                Upload the stores downloaded from custom url.
                            </p>

                            <div class="form-group">
                                <label for="task-name" class="control-label">Url</label>

                                <input type="text" name="name" id="task-name" class="form-control"
                                       value="{{ old('task') }}">
                            </div>

                            <div class="form-group">
                                @include ('partials._upload')
                            </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="messages">
                        <form action="{{ url('stores.upload')}}" method="POST"
                              class="form form--upload">
                            {{ csrf_field() }}

                            <p>
                                Upload custom XML file with store data.
                            </p>

                            <div class="form-group">
                                <label for="task-name" class="control-label">Url</label>

                                <input type="file" name="name" id="task-name" class="form-control"
                                       value="{{ old('task') }}">
                            </div>

                            <div class="form-group">
                                @include ('partials._upload')
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="xml-results hidden">
                <h3 class="text-center">
                    Data parsing results
                </h3>
                <div class="status-info">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="status status--warning">
                                Updated<br><span></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="status status--success">
                                Added<br><span></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="status status--error">
                                Errors<br><span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <ol class="list--status">

                </ol>
            </div>
        </section>
    </div>
@endsection