
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{$title}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-left">
                    @foreach($urls as $url)
                    <li class="breadcrumb-item @if(!$url['url']) active @endif">@if($url['url']) <a href="{{$url['url']}}">@endif{{$url['name']}}@if($url['url'])</a>@endif</li>
                    @endforeach
                </ol>
            </div><!-- /.col -->

        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
