@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><i class="fa fa-list"></i> {{ __('Posts List') }}</div>

                    <div class="card-body">
                        @session('success')
                            <div class="alert alert-success" role="alert">
                                {{ $value }}
                            </div>
                        @endsession

                        <div id="notification">

                        </div>

                        @if(!auth()->user()->is_admin)
                            <p><strong>Create New Post</strong></p>
                            <form method="post" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Title:</label>
                                    <input type="text" name="title" class="form-control" />
                                    @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Body:</label>
                                    <textarea class="form-control" name="body"></textarea>
                                    @error('body')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mt-2 form-group">
                                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-save"></i>
                                        Submit</button>
                                </div>
                            </form>
                        @endif

                        <p class="mt-4"><strong>Post List:</strong></p>
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th width="70px">ID</th>
                                    <th>Title</th>
                                    <th>Body</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($posts as $post)
                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td>{{ $post->title }}</td>
                                        <td>{{ $post->body }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">There are no posts.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @if(auth()->user()->is_admin)
        <script type="module">
            window.Echo.channel('posts')
                .listen('.create', (data) => {
                    console.log('New post event:', data);

                    // Show notification
                    var d1 = document.getElementById('notification');
                    d1.insertAdjacentHTML('beforeend', '<div class="alert alert-success alert-dismissible fade show"><span><i class="fa fa-circle-check"></i>  ' + data.message + '</span></div>');

                    // Parse created_at and title from message or send whole post in event data for better usage
                    // Here, let's assume you also send the post object in event payload (better to send)
                    const post = data.post;
                    if(post) {
                        // Create a new row with post data
                        var tbody = document.querySelector('.data-table tbody');
                        var newRow = `<tr>
                    <td>${post.id}</td>
                    <td>${post.title}</td>
                    <td>${post.body}</td>
                </tr>`;
                        tbody.insertAdjacentHTML('beforeend', newRow);
                    }
                });
        </script>
    @endif
@endsection
