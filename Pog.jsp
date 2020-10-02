<%@ page language="java" contentType="text/html; charset=UTF-8"
pageEncoding="UTF-8"%>
<%@ page import="java.sql.*, java.awt.FlowLayout, java.awt.image.BufferedImage, java.io.File, java.io.IOException, javax.imageio.ImageIO, javax.swing.ImageIcon, javax.swing.JFrame, javax.swing.JLabel"%>
<html>
<style>
h1 {text-align: center;}
p {text-align: center;}
</style>
<head>
<title>Pog</title>
</head>
<body>
<h1> <span style='color:#C5DBFF;font-family:"Comic Sans MS"; font-size:100px'>POG</h1>
<% 
	String db = "Pog";
    String user;
    user = "root";
    String password = "liangjiachang";
    try {
		java.sql.Connection con; 
		Class.forName("com.mysql.jdbc.Driver"); 
		con = DriverManager.getConnection("jdbc:mysql://localhost:3306/"+db,user, password);		

		Statement stmt = con.createStatement();
		ResultSet rs = stmt.executeQuery("SELECT * FROM pog.videos");
		int counter = 1;
		while (rs.next())
		{
			String fileLocation = rs.getString(1);
			String creator = rs.getString(2);
			String title = rs.getString(3);
			out.println("<p>" + fileLocation + "</p>");
			out.println("<p font-size:200px'>👍​👎" + title + "</p>");
			out.println("<p>" + creator + "</p>");
		}
		stmt.close();
		con.close();
	} catch(SQLException e) { 
		out.println("SQLException caught: " + e.getMessage()); 
    }
%>
<body style="background-color:#64A0FF;">	
</body>
</html>
