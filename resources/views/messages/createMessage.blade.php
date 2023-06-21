



@extends('layouts.main')

@section('header')
    @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'پیام' , 'url' => route('showMessage' , $ticket->id)), array('name'=> 'ایجاد پیام' , 'url' => null) ) @endphp
    @include('layouts.partials.header',['title' => 'ایجاد پیام' , 'urls' => $urls ])
@endsection

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">ایجاد پیام</h3>
        </div>
            <!-- DIRECT CHAT PRIMARY -->
           
                <div class="card card-primary card-outline direct-chat direct-chat-primary">
              
              <!-- /.card-header -->
              <div class="card-body">
                <!-- Conversations are loaded here -->
                <div class="direct-chat-messages">
                  <!-- Message. Default to the left -->
                    <div class="direct-chat-msg">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name float-left"></span>
                        <span class="direct-chat-timestamp float-right"></span>
                      </div>
                      <!-- /.direct-chat-info -->
                      <!-- /.direct-chat-img -->
                      <!-- /.direct-chat-text -->
                    </div>
                  <!-- /.direct-chat-msg -->

                  <!-- Message to the right -->
                    <div class="direct-chat-msg right">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name float-right"></span>
                        <span class="direct-chat-timestamp float-left"></span>
                      </div>
                      <!-- /.direct-chat-info -->
                      {{-- <img class="direct-chat-img" src="../dist/img/user3-128x128.jpg" alt="Message User Image"> --}}
                      <!-- /.direct-chat-img -->
                      <!-- /.direct-chat-text -->
                    </div>
                  <!-- /.direct-chat-msg -->
                </div>
                <!--/.direct-chat-messages-->

                <!-- Contacts are loaded here -->
                <div class="direct-chat-contacts">
                  <ul class="contacts-list">
                    <li>
                      <a href="#">
                        {{-- <img class="contacts-list-img" src="../dist/img/user1-128x128.jpg"> --}}

                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            حسام موسوی
                            <small class="contacts-list-date float-left">2/28/2015</small>
                          </span>
                          <span class="contacts-list-msg">تا حالا کدوم گوری بودی ؟...</span>
                        </div>
                        <!-- /.contacts-list-info -->
                      </a>
                    </li>
                    <!-- End Contact Item -->
                  </ul>
                  <!-- /.contatcts-list -->
                </div>
                <!-- /.direct-chat-pane -->
              </div>
              <!-- /.card-body -->
              @if ($ticket->status != 2)
                <div class="card-footer">
                  <form action="{{route('createMessage',$ticket->id) }}" method="post">
                    @csrf
                    <div class="input-group">
                      {{-- <input type="hidden" name="user_id" value="{{$user->id}}" > --}}
                      <input type="hidden" name="ticket_id" value="{{$ticket->id}}" >
                      <input type="text" name="text" placeholder="تایپ پیام ..." class="form-control @error('user_id') is-invalid @enderror">
                      @error('user_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                          <span class="input-group-append">
                            <button type="submit" class="btn btn-primary">ارسال</button>
                          </span>
                    </div>
                  </form>
                </div>
              @endif
              <!-- /.card-footer-->
            </div>
            
            <!--/.direct-chat -->
           
            
    </div>






@endsection










