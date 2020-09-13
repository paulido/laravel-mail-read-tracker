@extends('layouts.main')

@section('style')

<link href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet"> 
<link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
<link href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('dist/css/adminlte.min.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
@endsection
<meta name="csrf-token" content="{{csrf_token()}}">
@section('notification')
<style>
    .overlay{
        display: none;
        position: fixed;
        width: 100%;
        height: 20%;
        top: 0;
        left: 0;
        z-index: 999;
        background: rgba(255,255,255,0.8) url("/images/loader2.gif") center no-repeat;
    }
    /* Make spinner image visible when body element has the loading class */
    body.loading .overlay{
        display: block;
    }
</style>
<div class="overlay"></div>
@endsection
@section('content')
<style>
.fa-check-double{
  color:grey;
}
</style>
<section class="content">
  <div class="row">
        <div class="col-md-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <div class="card-tools">
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm btn-delete "><i class="far fa-trash-alt"></i></button>
                  <button type="button" class="btn btn-default btn-sm"><i class="fas fa-reply"></i></button>
                  <button type="button" class="btn btn-default btn-sm"><i class="fas fa-share"></i></button>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm btn-reload"><i class="fas fa-sync-alt"></i></button>
                <div class="float-right">
                  1-50/200
                  <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-left"></i></button>
                    <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
                  </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.float-right -->
              </div>
          <div class="table-responsive mailbox-messages">
          <table class="table table-hover table-striped">
            <tbody>
                <?php $i = 0;?>
                @foreach($mails as $mail)
                  @foreach($mail->report as $report)
                  <?php 
                    $color = 'grey';
                    if($report->clics > 0) 
                      $color = 'green';
                      $checkbox_id = 'check'. strval(++$i);
                  ?>
                    <tr>
                      <td>
                        <div class="icheck-primary">
                          <input type="checkbox" value="{{$mail->id}}" id="{{$checkbox_id}}">
                          <label for="{{$checkbox_id}}"></label>
                        </div>
                      </td>
                      <!-- <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td> -->
                      <td class="mailbox-star"><a href="#"><i class='fas fa-check-double' style='font-size:12px;color:{{$color}}'></i></a></td>
                      <td class="mailbox-name"><a href="{{route('mail.read',$mail->id)}}">{{$report->receiverUser->name}}</a></td>
                      <td class="mailbox-subject"><a href="{{route('mail.read', $mail->id)}}"><b>{{$mail->subject}}</b> - {{$mail->body}}</a>
                      </td>
                      <td class="mailbox-attachment"></td>
                      <td class="mailbox-date">{{$mail->created_at}}</td>
                    </tr>
                  @endforeach
                @endforeach
            </tbody>
          </table>
          <!-- /.table -->
        </div>

        <!-- /.mail-box-messages -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer p-0">
          <div class="mailbox-controls">
            <!-- Check all button -->
            <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
            </button>
            <div class="btn-group">
              <button type="button" class="btn btn-default btn-sm btn-delete" ><i class="far fa-trash-alt"></i></button>
              <button type="button" class="btn btn-default btn-sm"><i class="fas fa-reply"></i></button>
              <button type="button" class="btn btn-default btn-sm"><i class="fas fa-share"></i></button>
            </div>
            
            <!-- /.btn-group -->
            <button type="button" class="btn btn-default btn-sm btn-reload"><i class="fas fa-sync-alt"></i></button>
            <div class="float-right">
              1-50/200
              <div class="btn-group">
                <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-left"></i></button>
                <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
              </div>
              <!-- /.btn-group -->
            </div>
            <!-- /.float-right -->
          </div>
        </div>
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
  </section>

@endsection


@section('script')

<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>

<script>

  $(function () {
    //Enable check and uncheck all functionality
    $('.checkbox-toggle').click(function () {
      var clicks = $(this).data('clicks')
      if (clicks) {
        //Uncheck all checkboxes
        $('.mailbox-messages input[type=\'checkbox\']').prop('checked', false)
        $('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
      } else {
        //Check all checkboxes
        $('.mailbox-messages input[type=\'checkbox\']').prop('checked', true)
        $('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass('fa-check-square')
      }
      $(this).data('clicks', !clicks)
    })

    //Handle starring for glyphicon and font awesome
    $('.mailbox-star').click(function (e) {
      e.preventDefault()
      //detect type
      var $this = $(this).find('a > i')
      var glyph = $this.hasClass('glyphicon')
      var fa    = $this.hasClass('fa')

      //Switch states
      if (glyph) {
        $this.toggleClass('glyphicon-star')
        $this.toggleClass('glyphicon-star-empty')
      }

      if (fa) {
        $this.toggleClass('fa-star')
        $this.toggleClass('fa-star-o')
      }
    })
  })
</script>

<script>
  //send ajax reload request
  $(function(){
    $(document).on('click', '.btn-reload', function(e){
      e.preventDefault();
      $.ajax({
        url:'/home/sent',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        method:'get',
        success:function(data){
            //alert('success');
            location.reload();
        },
        error:function(xhr)
        {
            //alert('failed');
        }
      });
    });
  });
</script>

<script>
  // ajax for delete purpose

$(document).ready(function(){
 
 $('.btn-delete').click(function(){
  //  alert('deletion start');
  if(confirm("Are you sure you want to delete this?"))
  {
   var id = [];
   var isdel = 'yes';
   $(':checkbox:checked').each(function(i){
    id[i] = $(this).val();
   });
   
   if(id.length === 0) //tell you if the array is empty
   {
    alert("Please Select atleast one checkbox");
   }
   else
   {
    $.ajax({
    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
     url:'/mail/movetrash/'+ id + '/' + isdel,
     method:'get',
     data:{
       id:id,
       isdel:isdel,
       },
      dataType:'json',
     success:function()
     {
      for(var i=0; i<id.length; i++)
      {
       $('tr#'+id[i]+'').css('background-color', '#ccc');
       $('tr#'+id[i]+'').fadeOut('slow');
      }
      location.reload();
     }
     
    });
   }
   
  }
  else
  {
   return false;
  }
 });
 
});
</script>

<script>
//handle loader while reloading
// Add remove loading class on body element depending on Ajax request status
$(document).on({
  ajaxStart: function(){
      $("body").addClass("loading"); 
  },
  ajaxStop: function(){ 
      $("body").removeClass("loading"); 
  }    
});
</script>

<script src="{{asset('dist/js/demo.js')}}"></script>
@endsection