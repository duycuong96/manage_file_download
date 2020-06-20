@extends('layouts.app')

@section('content')

<!-- Modal upload file -->
<div class="modal fade" id="uploadFile" tabindex="-1" role="dialog" aria-labelledby="uploadFileLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="card bg-primary text-white">
                <div class="card-header">
                    <h3><i class="fas fa-file-upload"></i> Upload file</h3>
                </div>
            </div>

            <div class="card card-body">
                <form action=" {{ route('uploadFile') }}
                                            " method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="path" value="{{ $path }}">
                    <div class="input-group mb-6">
                        <div class="input-group mb-3">
                            <input type="file" name="file" id="file" onchange="check(this)" required
                                class="form-control" name="name" placeholder="Folder name" aria-label="Folder name"
                                aria-describedby="button-addon2">
                            <div class="input-group-append">
                                <button id="upload-button" class="btn btn-primary" type="submit" id="button-addon2"
                                    disabled>
                                    <i class="fas fa-file-upload"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal add folder -->
<div class="modal fade" id="addFolder" tabindex="-1" role="dialog" aria-labelledby="addFolderLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="card bg-warning" style="color: Violet">
                <div class="card-header">
                    <h3><i class="fas fa-folder-plus"></i> Add folder</h3>
                </div>
            </div>
            <div class="card card-body">

                <form class="form-group" action="{{ route('storeFolder') }}" method="post">
                    @csrf
                    <input type="hidden" name="path" value="{{ $path }}">
                    <div class="input-group mb-6">
                        <input type="text" class="form-control" id="folder-name" onchange="checkFolder(this)"
                            name="name" placeholder="Folder name" aria-label="Folder name"
                            aria-describedby="button-addon2">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary" id="add-folder" type="button"
                                id="button-addon2" disabled>
                                <i class="fas fa-folder-plus"></i>
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header text-primary">
                    <a class="h1" href="{{ route('home') }}">
                        <i class="fas fa-share-alt"></i>
                    </a>
                <div class="btn-group float-right">
                        <!-- Button trigger modal -->
                        <button data-toggle="modal" class="btn btn-primary mr-2" data-target="#uploadFile">
                            Upload file
                        </button>
                        <button data-toggle="modal" class="btn btn-warning" data-target="#addFolder">
                            Add folder
                        </button>
                </div>
                </div>
                <div class="card-body">

                    <div class="alert" role="alert" id="notice" hidden></div>
                    <div class="row">

                    </div>

                    <table id="table" class="table table-hover mt-3">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col"></th>
                                <th scope="col">Tên</th>
                                <th scope="col">Kích thước</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($listDirectorie as $directorie)
                                <tr id="drop" ondrop="drop(event)" ondragover="allowDrop(event)"
                                    data-path="{{ $directorie['path'] }}">
                                    <th scope="row"> {{ $loop->iteration }}
                                        <label id="path-directorie"
                                            hidden>{{ $directorie['path'] }}</label></th>
                                    <td class="text-warning h3"><i class="fas fa-folder"></i></td>
                                    <td><a
                                            href="{{ route('showFolder') }}?path={{ urlencode($directorie['path']) }}">{{ $directorie['name'] }}</a>
                                    </td>
                                    <td class="h1"> - </td>
                                    <td>
                                        {{-- <a href="{{ route('download') }}?path={{ urlencode($directorie['path']) }}"
                                        class="btn btn-success mr-2">
                                        <i class="fas fa-download"></i>
                                        </a> --}}
                                        <form action="{{ route('destroyFolder') }}" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <input type="hidden" name="path"
                                                value="{{ urlencode($directorie['path']) }}">
                                            <button class="btn btn-danger" onclick="return confirm_delete()">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            @foreach($listFile as $file)
                                <tr onclick="nameId()" id="drag" draggable="true" ondragstart="drag(event)"
                                    data-path="{{ $file['path'] }}">
                                    <th scope="row">
                                        {{ $loop->iteration }}
                                        <label id="path-file" hidden>{{ $file['path'] }}</label>
                                    </th>
                                    <td class="text-primary h3">

                                        @switch($file['type'])
                                            @case('application/zip')
                                                <i class='far fa-file-archive text-danger'></i>
                                                @break
                                            @case('image/jpeg')
                                                <i class='far fa-file-image'></i>
                                                @break
                                            @case('application/msword')
                                                <i class='far fa-file-word'></i>
                                                @break
                                            @default
                                            <i class='far fa-file'></i>
                                        @endswitch
                                    </td>
                                    <td id="name-file"> {{ $file['name'] }} </td>
                                    <td> {{ number_format($file['size'], 2) }} MB</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('download') }}?path={{ urlencode($file['path']) }}"
                                                class="btn btn-success mr-2">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <form action="{{ route('destroyFile') }}" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <input type="hidden" name="path"
                                                    value="{{ urlencode($file['path']) }}">
                                                <button class="btn btn-danger" onclick="return confirm_delete()">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
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
<script>
    function confirm_delete() {
        return confirm("Bạn có muốn xóa file này không?");
    }

    function showNotice(message, type) {
        $('#notice').text(message).attr('class', 'alert alert-' + type).prop('hidden', false);
    }

    function hideNotice() {
        $('#notice').prop('hidden', true);
    }


    var CheckNameExists = "{{ route('CheckNameExists') }}";
    var moveFile = "{{ route('moveFile') }}";

    function check(fileInput) {
        var files = fileInput.files;
        if (files.length == 0) return;

        var name = files[0].name;
        var path = "{{ $path }}";

        console.log(path);
        $.ajax({
            type: 'GET',
            url: CheckNameExists,
            data: {
                name: name,
                path: path
            },

            success: function (data) {
                console.log(data);
                if (data['success'] == true) {
                    showNotice('File đã tồn tại không thể upload.', 'danger');
                    $('#upload-button').prop('disabled', true);

                } else {
                    hideNotice();
                    $('#upload-button').prop('disabled', false);
                }
            }
        });
    }

    function checkFolder() {
        var name = $("#folder-name").val();
        var path = "{{ $path }}";
        console.log(name);
        $.ajax({
            type: 'GET',
            url: CheckNameExists,
            data: {
                name: name,
                path: path
            },

            success: function (data) {

                if (data['success'] == true) {
                    showNotice('Folder đã tồn tại không thể thêm.', 'danger');
                    $('#add-folder').prop('disabled', true);

                } else {
                    hideNotice();
                    $('#add-folder').prop('disabled', false);
                }
            }
        });
    }

</script>

<script>
    function nameId() {
        attrName = $('#drag').attr('.label');
        console.log(attrName);
    }

    function allowDrop(ev) {
        ev.preventDefault();
        // console.log(ev.preventDefault());
    }

    function drag(ev) {
        ev.dataTransfer.setData("filePath", $(ev.target).data('path'));
    }

    function drop(ev) {
        const filePath = ev.dataTransfer.getData('filePath');
        const dirPath = $(ev.target).closest('tr').data('path');

        // console.log(filePath, dirPath);

        $.get(moveFile, {
            pathFile: filePath,
            pathDirectorie: dirPath
        }, data => {
            if (data['success'] == true) {
                showNotice('file đã tồn tại không thể di chuyển.', 'danger');

            } else {
                showNotice('Di chuyển file thành công', 'success');
                $("#table").load(" #table");
            }
        });


    }

</script>
@if(session('status'))
    <script>
        showNotice('{{ session('status') }}', 'success');

    </script>
@endif

@endsection
