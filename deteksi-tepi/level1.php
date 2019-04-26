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
	<!-- <?php //session_start();?> -->
	<div class="container">
		<center>
			<div class="page-header">
				<h2>Tebak Pola Gambar</h2>
			</div>
			

			<div class="row">
				<div class="col-md-4 col-md-offset-4">
					<img src="./img/soal1.png" alt="">
				</div>
			</div>
			<br>
			<p>Manakah gambar yang sesuai dengan pola gambar diatas ?</p>
			<br>
			<form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data">
				<div class="row">
					<div class="col-md-2 col-md-offset-2">
						<img class="img-responsive" src="./img/cow1.png" alt="">
						<br><br>
						<button type="submit" class="btn btn-primary" name="A"> A </button>
					</div>

					<div class="col-md-2">
						<img class="img-responsive" src="./img/cow2.png" alt="">
						<br><br>
						<button type="submit" class="btn btn-primary" name="B"> B </button>
					</div>	

					<div class="col-md-2 ">
						<img class="img-responsive" src="./img/cow3.png" alt="">
						<br><br>
						<button type="submit" class="btn btn-primary" name="C"> C </button>
					</div>

					<div class="col-md-2">
						<img class="img-responsive" src="./img/cow4.png" alt="">
						<br><br>
						<button type="submit" class="btn btn-primary" name="D"> D </button>
					</div>	
				</div>
			</form>

			<?php 
			
			function get_luminance($pixel)
			{
				$pixel = sprintf('%06x',$pixel);
				$red = hexdec(substr($pixel,0,2))*0.30;
				$green = hexdec(substr($pixel,2,2))*0.59;
				$blue = hexdec(substr($pixel,4))*0.11;
				return $red+$green+$blue;
			}
			?>

			<div class="col-md-4 col-md-offset-4">
				<?php 
				if (isset($_POST['A'])) 
				{
					$image_name = "./img/cow1.png";
					game($image_name);
				}
				elseif (isset($_POST['B']))
				{ 
					$image_name = "./img/cow2.png";
					game($image_name);
				}
				elseif (isset($_POST['C']))
				{ 
					$image_name = "./img/cow3.png";
					game($image_name);
				}
				elseif (isset($_POST['D']))
				{ 
					$image_name = "./img/cow4.png";
					game($image_name);
				}
				else
				{
					echo "";
				}

		//Deteksi Tepi
				
				function game($image_name)
				{
					error_reporting(0);
					$image_source = imagecreatefrompng($image_name);

					$lebar = imagesx($image_source);
					$tinggi = imagesy($image_source);

					$jawaban = imagecreatetruecolor($lebar,$tinggi);

					for ($x = 1; $x < $lebar-1; $x++) 
					{
						for ($y = 1; $y < $tinggi-1; $y++) 
						{
				
				$pixel1 = get_luminance(imagecolorat($image_source,$x-1,$y-1)); //1
				$pixel2 = get_luminance(imagecolorat($image_source,$x,$y-1)); //2
				$pixel3 = get_luminance(imagecolorat($image_source,$x+1,$y-1)); //3
				$pixel4 = get_luminance(imagecolorat($image_source,$x-1,$y)); //4
				$pixel6 = get_luminance(imagecolorat($image_source,$x+1,$y)); //6
				$pixel7 = get_luminance(imagecolorat($image_source,$x-1,$y+1)); //7
				$pixel8 = get_luminance(imagecolorat($image_source,$x,$y+1)); //8
				$pixel9 = get_luminance(imagecolorat($image_source,$x+1,$y+1)); //9

        		//konvulasi
				$conv_x = ($pixel3+($pixel6*2)+$pixel9)-($pixel1+($pixel4*2)+$pixel7); //Sx
				$conv_y = ($pixel1+($pixel2*2)+$pixel3)-($pixel7+($pixel8*2)+$pixel9); //Sy


				$pixel5 = sqrt($conv_x*$conv_x+$conv_y*$conv_y); //M

				$pixel5 = 255-$pixel5; // menegatifkan hasil biar jadi background putih garis hitam

      			//mengatur agar nilai menjadi sesuai ketentuan
				if($pixel5 > 255)
				{
					$pixel5 = 255;
				}
				if($pixel5 < 0)
				{
					$pixel5 = 0;
				}

        		//menyimpan nilai ke pixel5
				$new_pixel5 = imagecolorallocate($jawaban,$pixel5,$pixel5,$pixel5);

        		//memasukkan ke gambar baru       
				imagesetpixel($jawaban,$x,$y,$new_pixel5);                       
			}
		}

		imagepng($jawaban,'jawaban1.png');


				// Pengurangan Image
		$soal = "./img/soal1.png";
		$soal1 = imagecreatefrompng($soal);
		$jawaban1 = $jawaban;

		for($x=0;$x<imagesx($soal1);++$x)
		{
			for($y=0;$y<imagesy($soal1);++$y)
			{
				$index1 = imagecolorat($soal1, $x, $y);
				$index2 = imagecolorat($jawaban1, $x, $y);



				$red = ($index1 >> 16) & 0xFF;
				$green = ($index1 >> 8) & 0xFF;
				$blue = $index1 & 0xFF;

				$red2 = ($index2 >> 16) & 0xFF;
				$green2 = ($index2 >> 8) & 0xFF;
				$blue2 = $index2 & 0xFF;

				$red3 = $red3+($red-$red2);
				$green3 = $green3+($green-$green2);
				$blue3 = $blue3+($blue2-$blue);

			}

		}  



		if($red3!=0 || $green3!=0 || $blue3!=0)    
			
		{
			header('location:gagal.php');
		}
		else
		{
			header('location:berhasil.php');
		}

		return $true;
	}

	?>

</div>	
</center>
</div>



<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/Chart.min.js"></script>
</body>
</html>