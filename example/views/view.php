<table id="grilla" class="fondotablatitulos" >
<tr><td colspan="7"><div style="text-align: right;" >
  <a class="fancynormal" href="<?php echo base_url(); ?>inventario/agregar"><img src="<?php echo base_url(); ?>assets/images/grid/add.png" border="0" title="Agregar nuevo portafolio" /></a>
</div></td></tr>                                                                                               
 <tr class="headergrid">
   <td valign="middle" align="center"  width="50">N°</td>
   <td valign="middle" align="center"  width="100"><a href="<?php echo base_url($base.'/id/desc'); ?>">Codigo</a></td>
   <td valign="middle" align="left" width="300"><a href="<?php echo base_url($base.'/inventario/desc'); ?>">Nombre Inventario</a></td>
   <td valign="middle" align="left" width="150"><a href="<?php echo base_url($base.'/usuario/desc'); ?>">Responsable</a></td>
   <td colspan="2" align="center" valign="middle" width="50">Acción</td>
 </tr>

 <?php
  for ($i=0; $i < $datos['GPagerows']; $i++):
  
  $classtr = ($i%2) ? "graygrid" : "normalgrid" ;
  $onmouse = (!empty($datos['GData'][$i]['id'])) ? "onMouseOver='this.style.background=\"#CCCC99\"' onMouseOut='this.style.background=\"\"'" : "";
 ?>

  <tr class='bodygrid <?php echo $classtr; ?>' <?php echo $onmouse; ?> >

   <td align="center" valign="top"><?php echo (!empty($datos['GData'][$i]['id'])) ? ($i+1)+$datos['GSegm'] : ''; ?></td>
   <td align="center" valign="top"><?php echo $datos['GData'][$i]['id']?></td>
                                               
   <td align="left" valign="top" title="<?php echo $datos['GData'][$i]['inventario']?>"><?php echo substr($datos['GData'][$i]['inventario'], 0, 60);?></td>

   <td align="left" valign="top"><?php echo $datos['GData'][$i]['usuario'];?></td>
                                              
   <td align="right" valign="top">
   
      <div align="center">
        <?php if (!empty($datos['GData'][$i]['id'])): ?>
         <a class="fancysmall" href="<?php echo base_url() ?>inventario/eliminar/<?php echo $datos['GData'][$i]['id'] ?>"><img src="<?php echo base_url() ?>assets/images/grid/eliminar.png" border="0" title="Eliminar" /></a>
         <a class="fancynormal" href="<?php echo base_url() ?>inventario/editar/<?php echo $datos['GData'][$i]['id'] ?>"><img src="<?php echo base_url() ?>assets/images/grid/editar.png" border="0" title="Editar" /></a>
         <?php endif; ?>
      </div>

   </td>
 
 </tr>
 <?php
  endfor;
 ?>    
  <tr class="footergrid">
   <td colspan="7" valign="middle">
      <div style="float: left">Total Registros: <?php echo $datos['GTotalreg']; ?></div>
      <div style="float: right" class="paginador"><?php echo $datos['GPagin']; ?></div>
   </td>
 </tr>  
</table>
