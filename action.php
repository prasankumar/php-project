<?php
	session_start();
	include 'config.php';

	$update=false;
	$id="";
	$name="";
	$email="";
	$phone="";
	$photo="";

	if(isset($_POST['add'])){
		$name=$_POST['name'];
		$email=$_POST['email'];
		$phone=$_POST['phone'];

		$photo=$_FILES['image']['name'];
		$upload="C:/xampp/htdocs/CRUDVIP/upload/".$photo;

		$query="insert INTO crud(name,email,phone,photo)VALUES(?,?,?,?)";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("ssss",$name,$email,$phone,$upload);
		$stmt->execute();
		move_uploaded_file($_FILES['image']['tmp_name'], $upload);

		header('location:index.php');
		$_SESSION['response']="Successfully Inserted to the database!";
		$_SESSION['res_type']="success";
	}
	if(isset($_GET['delete'])){
		$id=$_GET['delete'];

		$sql="select photo FROM crud WHERE id=?";
		$stmt2=$conn->prepare($sql);
		$stmt2->bind_param("i",$id);
		$stmt2->execute();
		$result2=$stmt2->get_result();
		$row=$result2->fetch_assoc();

		$imagepath=$row['photo'];
		unlink($imagepath);

		$query="delete FROM crud WHERE id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();

		header('location:index.php');
		$_SESSION['response']="Successfully Deleted!";
		$_SESSION['res_type']="danger";
	}
	if(isset($_GET['edit'])){
		$id=$_GET['edit'];

		$query="select * FROM crud WHERE id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result=$stmt->get_result();
		$row=$result->fetch_assoc();

		$id=$row['id'];
		$name=$row['name'];
		$email=$row['email'];
		$phone=$row['phone'];
		$photo=$row['photo'];

		$update=true;
	}
	if(isset($_POST['update'])){
		$id=$_POST['id'];
		$name=$_POST['name'];
		$email=$_POST['email'];
		$phone=$_POST['phone'];
		$oldimage=$_POST['oldimage'];

		if(isset($_FILES['image']['name'])&&($_FILES['image']['name']!="")){
			$newimage="C:/xampp/htdocs/CRUDVIP/upload/".$_FILES['image']['name'];
			unlink($oldimage);
			move_uploaded_file($_FILES['image']['tmp_name'], $newimage);
		}
		else{
			$newimage=$oldimage;
		}
		$query="update crud SET name=?,email=?,phone=?,photo=? WHERE id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("ssssi",$name,$email,$phone,$newimage,$id);
		$stmt->execute();

		$_SESSION['response']="Updated Successfully!";
		$_SESSION['res_type']="primary";
		header('location:index.php');
	}

	if(isset($_GET['details'])){
		$id=$_GET['details'];
		$query="select * FROM crud WHERE id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result=$stmt->get_result();
		$row=$result->fetch_assoc();

		$vid=$row['id'];
		$vname=$row['name'];
		$vemail=$row['email'];
		$vphone=$row['phone'];
		$vphoto=$row['photo'];
	}
?>