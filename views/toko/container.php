<!DOCTYPE HTML>
    <head>
    	<meta http-equiv="content-type" content="text/html" />
    	<meta name="Quad Rumah Software 2015" content="Dashboard Admin" />
    
		<?php
			if(!empty($title))
			{
				echo'<title>'.$title.'</title>';
			}
			else
			{
				echo'<title>TOKO ONLINE</title>';
			}
		?>
    	
        
        <!-- view source dll.-->
        <!--<script type="text/javascript">
        window.addEventListener("keydown",function(e){if(e.ctrlKey&&(e.which==65||e.which==66||e.which==67||e.which==73||e.which==80||e.which==83||e.which==85||e.which==86)){e.preventDefault()}});document.keypress=function(e){if(e.ctrlKey&&(e.which==65||e.which==66||e.which==67||e.which==73||e.which==80||e.which==83||e.which==85||e.which==86)){}return false}
        </script>
        <script type="text/javascript">
        document.onkeydown=function(e){e=e||window.event;if(e.keyCode==123||e.keyCode==18){return false}}
        </script>-->
        
        <!-- mengaktifkan javascript-->
        <!--<div align="center"><noscript>
           <div style="position:fixed; top:0px; left:0px; z-index:3000; height:100%; width:100%; background-color:#FFFFFF">
           <div style="font-family: Arial; font-size: 17px; background-color:#00bbf9; padding: 11pt;">Mohon aktifkan javascript pada browser untuk mengakses halaman ini!</div></div>
        </noscript></div>-->
        
        <!--kanan-->
        <!--<script type="text/javascript">
        function mousedwn(e){try{if(event.button==2||event.button==3)return false}catch(e){if(e.which==3)return false}}document.oncontextmenu=function(){return false};document.ondragstart=function(){return false};document.onmousedown=mousedwn
        </script>-->
        
        
        <!-- jQuery 2.1.4 -->
        <script src="<?=base_url();?>assets-toko/adminlte/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    </head>
    
    <body class="skin-blue sidebar-mini" onLoad="JavaScript:loadSearchHighlight();">
        <div class="wrapper"> <!-- Buka | Untuk Wrapper/Container -->
            
            <!--<div> <!-- Buka | Untuk Header -->
                <?php
                    $this->load->view('toko/header');
                ?>
            <!--</div> <!-- Tutup | Untuk Header -->
            
            
                <!-- Buka | Untuk Sidebar-->
                   <?php 
                        $this->load->view('toko/sidebar');
                   ?> 
                <!-- Tutup | Untuk Sidebar -->
                
                
                
                    <?php 
                        $this->load->view('toko/page/'.$page_content.'.html');
                        //$this->load->view('toko/page/'.$page_content);
                    ?>
                

            
            <div> <!-- Buka | Untuk Footer-->
                <?php 
                    $this->load->view('toko/footer');
                ?>
            </div> <!-- Tutup | Untuk Footer -->
            
            <div> <!-- Buka | Sidebar control-->
                <?php 
                    $this->load->view('toko/control_sidebar');
                ?>
            </div> <!-- Tutup | Sidebar Control -->
            
        </div> <!-- Tutup | Untuk Wrapper/Container -->
        
         
        <!-- AdminLTE for demo purposes -->
        <script src="<?=base_url();?>assets-toko/adminlte/dist/js/demo.js"></script>
        <!-- page script -->
        
    </body>
</html>