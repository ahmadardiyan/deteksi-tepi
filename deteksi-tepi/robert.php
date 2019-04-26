<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title> DETEKSI TEPI </title>

	<link href="css/bootstrap.min.css" rel="stylesheet" >
	<link href="style.css" rel="stylesheet" >

</head>
<body>
	<div class="container">
		<center>
			<h1 class="page-header"> 
				<b>DETEKSI TEPI</b>
			</h1>

			<form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data">

				<div class="row">
					<div class="col-md-4 col-md-offset-2"> 
						<input type="file" name="image" style="padding-bottom: 30px">

						<button type="submit" class="btn btn-success" name="submit"> Upload Image </button>
					</div>

					
					<div class="col-md-4" style="padding-top: 50px">
						<button type="submit" class="btn btn-success" name="sobel"> Robert </button>
					</div>
					
				</div>

			</form>

			<br><br>

			<div class="row">
				<div class="col-md-4 col-md-offset-2"> <!-- Upload Image And Image Ori -->
					<label> GAMBAR ASLI </label>
					<div class="row">
						<?php 
						error_reporting(0);

						session_start();
						
						// Upload
						if(isset($_POST["submit"])) 
						{
							$_SESSION['image_location']	= $_FILES["image"]["tmp_name"];
							$_SESSION['image_name']		= $_FILES["image"]["name"];
							$_SESSION['image_ext']		= strtolower(pathinfo($_SESSION['image_name'], PATHINFO_EXTENSION));
							$_SESSION['image_name_ori']	= "img-0.".$_SESSION['image_ext'];

							if (empty($_SESSION['image_name'])) 
							{
								?>
								<div class="alert alert-danger">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									<strong>Error!</strong> Masukkan gambar !
								</div>

								<?php
							}
							else 
							{
								if ($_SESSION['image_ext'] == "jpg" || $_SESSION['image_ext'] == "jpeg" || $_SESSION['image_ext'] == "png") 
								{
									if (copy($_SESSION['image_location'], $_SESSION['image_name_ori'])) {
										$_SESSION['image_status'] = "berhasil";
									}
									else 
									{
										?>
										<div class="alert alert-danger">
											<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
											<strong>Error!</strong> Gambar gagal diunggah
										</div>"

										<?php
									}
								}
								else 
								{
									?>
									<div class="alert alert-danger">
										<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										<strong>Error!</strong> Masukkan extensi gambar yang bertipe JPG/JPEG/PNG
									</div>

									<?php	
								}
							}
						}
						else 
						{
							$_SESSION['image_status'] = "tidak berhasil";
						}
						?>	
					</div>

					<div> <!-- Menampilkan Image Original -->
						<?php 
						if ($_SESSION['image_status'] = "berhasil") :?>		
						<img src="<?php echo $_SESSION['image_name_ori']; ?>" style="width: 300px"/>
					<?php endif; ?>
				</div> 
			</div>

			<div class="col-md-4"> 
				<label> DETEKSI TEPI ROBERT </label>

				<?php

				// Menampilkan Image Reduce
				if  ($_SESSION['image_status'] == "berhasil")  
				{
					if (isset($_POST['sobel']))
					{
						$_SESSION['image_sobel'] = "img-sobel.".$_SESSION['image_ext'];

						sobel($_SESSION['image_name_ori'], $_SESSION['image_sobel']);
						?>

						<img src="<?php echo $_SESSION['image_sobel']; ?>" style="width: 300px"/>

						<?php
					}  
				}
				else
				{
					echo "Masukkan Gambar";
				}
				?>
			</div>

		</center>
	</div>

	<?php

	// Luminance
	function get_luminance($pixel)
	{
		$pixel = sprintf('%06x',$pixel);
		$red = hexdec(substr($pixel,0,2))*0.30;
		$green = hexdec(substr($pixel,2,2))*0.59;
		$blue = hexdec(substr($pixel,4))*0.11;
		return $red+$green+$blue;
	}

	//Noise Gaussian
	function sobel($image_name, $image_sobel) 
	{

		if ($_SESSION['image_ext'] == "jpg" || $_SESSION['image_ext'] == "jpeg") 
		{
			$image_source = imagecreatefromjpeg($image_name);
		}
		elseif ($_SESSION['image_ext'] == "png") 
		{
			$image_source = imagecreatefrompng($image_name);
		}

		$lebar = imagesx($image_source);
		$tinggi = imagesy($image_source);

		$final = imagecreatetruecolor($lebar,$tinggi);

		for ($x = 1; $x < $lebar-1; $x++) 
		{
			for ($y = 1; $y < $tinggi-1; $y++)
			 {

       
				$pixel1 = get_luminance(imagecolorat($image_source,$x,$y)); //1			
				$pixel2 = get_luminance(imagecolorat($image_source,$x,$y+1)); //2
				$pixel3 = get_luminance(imagecolorat($image_source,$x+1,$y)); //3
				$pixel4 = get_luminance(imagecolorat($image_source,$x+1,$y+1)); //4
				
				$kernel_1 = $pixel1-$pixel4;
				$kernel_2 = $pixel2-$pixel3;

				$hasil = abs($kernel_1)+abs($kernel_2);
				$hasil = 255-$hasil;

				// if($hasil > 255)
				// {
				// 	$hasil = 255;
				// }
				// if($hasil < 0)
				// {
				// 	$hasil = 0;
				// }

       
				$new_hasil = imagecolorallocate($final,$hasil,$hasil,$hasil);

             
				imagesetpixel($final,$x,$y,$new_hasil);            
			}
		}

		if ($_SESSION['image_ext'] == "jpg" || $_SESSION['image_ext'] == "jpeg") 
		{
			imagejpeg($final, $_SESSION['image_sobel']);
		}
		elseif ($_SESSION['image_ext'] == "png") 
		{
			imagepng($final, $_SESSION['image_sobel']);
		}

		return $gray;
	}
	?>


	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/Chart.min.js"></script>
</body>
</html>