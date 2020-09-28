<%@ page language="java" contentType="text/html; charset=UTF-8"
pageEncoding="UTF-8"%>
<%@ page import="java.sql.*"%>
<html>
  <head>
    <title>Pog</title>
    </head>
  <body>
    <h1>Connection with Pog Database</h1>
    <% 
     String db = "Pog;
        String user; 
        user = "root";
        String password = "----";
        try {
            
            java.sql.Connection con; 
            Class.forName("com.mysql.jdbc.Driver"); 
            con = DriverManager.getConnection("jdbc:mysql://localhost:3306/"+db,user, password);
            
            out.println("Initial entries in table \"Videos\": <br/>");
            Statement stmt = con.createStatement();
            stmt.close();
            con.close();
        } catch(SQLException e) { 
            out.println("SQLException caught: " + e.getMessage()); 
        }
    %>
	<body style="background-color:purple;">
	<img src = "maxresdefault.jpg" alt = "minecraft"> <br>
	👍​👎 Minecraft Gameplay By: Thomas <br>	
	<img src = "league.jpg" alt = "lol"> <br>
	👍​👎 League of Legends Gameplay By: Thomas <br>	
  </body>
</html>