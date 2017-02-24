@extends('layouts.tampilan')

@section('content')
       <div id="content">
                <div class="panel">
                  <div class="panel-body">
                      <div class="col-md-6 col-sm-12">

                        <h3 class="animated fadeInLeft">Tabel Pegawai</h3>
           <p class="animated fadeInDown"><span class="fa  fa-map-marker"></span> Pegawai</p>

                       
                    </div>
                    <div class="col-md-0 col-sm-12">
                        
                        <div class="col-md-0 col-sm-0">
                        <a href="{{url('pegawai/create')}}"> <button class=" btn btn-circle btn-gradient btn-danger" value="primary">
                                <span class="fa  fa-pencil fa-1x"></span>
                              </button></a>
                             
                              
                                 
              

                        <div class="panel-body">
                            <div class="row">
                             <form action="{{url('pegawai')}}/?nip=nip"> <input type="text" name="nip"> <button type="submit" class="btn btn-primary">
                                    cari
                                </button>

                                </form>
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th>nip</th>
                                                    <th>Nama</th>
                                                    <th>Email</th>
                                                     <th>Permession</th>
                                                    <th>Nama Jabatan</th>
                                                    <th>Nama Golongan</th>
                                                    <th>photo</th>
                                                    <th colspan="6" >Opsi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($pegawai as $pegawais)
                                             @if(Auth::user()->permession=='Hrd')
                                             @if ($pegawais->user->permession=='Pegawai')
                                                <tr>
                                                    <td>{{$pegawais->nip}}</td>
                                                    <td>{{$pegawais->user->name}}</td>
                                                     <td>{{$pegawais->user->email}}</td>
                                                     <td>{{$pegawais->user->permession}}</td>
                                                    <td>{{$pegawais->jabatan->nama_jabatan}}</td>
                                                    <td>{{$pegawais->golongan->nama_golongan}}</td>
                                                    <td><img src="/assets/image/{{$pegawais->photo}}" width="50" height="50"></td>
                                                    <td>
              {!! Form::open(['method' => 'DELETE', 'route'=>['pegawai.destroy', $pegawais->id]]) !!}
             {!! Form::submit('Hapus', ['class' => 'btn btn-danger']) !!}
             {!! Form::close() !!}

             </td>
                     
                                                </tr>
                                               
                                                   @endif
                                                @else
                                                <tr>
                                                    <td>{{$pegawais->nip}}</td>
                                                    <td>{{$pegawais->user->name}}</td>
                                                     <td>{{$pegawais->user->email}}</td>
                                                     <td>{{$pegawais->user->permession}}</td>
                                                    <td>{{$pegawais->jabatan->nama_jabatan}}</td>
                                                    <td>{{$pegawais->golongan->nama_golongan}}</td>
                                                    <td><img src="/assets/image/{{$pegawais->photo}}" width="50" height="50"></td>
                                                    <td><a href="{{route('pegawai.edit',$pegawais->id)}}" class="btn btn-success">Ubah</a></td>
                    <td>
                        <td>
              <form method="POST" action=" {{route('pegawai.destroy', $pegawais->id)}} ">
                                {{csrf_field()}}
        <input type="hidden" name="_method" value="DELETE">
        <input class="btn btn-xs btn-danger" onclick="return confirm('Apakah yakin ingin menghapus data ?');" type="submit" value="Hapus">
                            </form>
             </td>
                      
                     
                                                </tr>
                                             @endif
                                                @endforeach

                                            </tbody>
                                        </table>
                                           <?php echo $pegawai->render(); ?>
                                    </div>

                                </div>

                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!--End simple table example -->

                </div>

               







                </div>

            </div>

                                     </div>
                          </div>
                        </div>                   
                    </div>
                  </div>                    
                </div>
@endsection