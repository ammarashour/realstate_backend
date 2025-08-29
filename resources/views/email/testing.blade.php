<!doctype html>
<html>
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Email Testing</title>
  </head>
  <body>
    @if (Session::has('success'))
    <div class="alert alert-success alert-dismissible mt-5 col-6 offset-3">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <strong>{{ Session::get('success') }}</strong>
      </div>
    @endif

    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible  mt-5 col-6 offset-3">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <strong>{{ Session::get('error') }}</strong>
        </div>
    @endif

    <form action="{{ route('send.mail.testing') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card card-info shadow col-6 mt-5 offset-3">
          <div class="card-body">
            <div class="form-group">
              <label for="email">Mail: </label>
              <input type="text" id="email" name="email" placeholder="Enter your mail" class="form-control">
            </div>
          </div>
          <div class="card-footer">
            <div class="float-right">
              <button type="reset" class="btn btn-sm btn-secondary">Cancel</button>
              <button type="submit" class="btn btn-sm btn-primary">Send</button>
            </div>
          </div>
        </div> <!-- /.card -->
      </form>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>
