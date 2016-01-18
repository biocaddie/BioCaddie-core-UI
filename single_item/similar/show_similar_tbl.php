<div class="panel panel-danger" id="similar">
  <div class="panel-heading">Similar datasets</div>

  <div class="panel-body">
    <div class="table-responsive ">
       <table class="table">

            <tbody>

              <?php foreach ($itemArray as $item):?>
                <tr>
                  <td>
                    <div class="similar sidebar">
                      <a href=<?php echo "http://www.rcsb.org/pdb/explore/explore.do?structureId=".$item -> get_pdbid();?>>
                        <?php 
                        $str = $item -> get_structureTitle();
                        echo substr($str,0,65);
                        ?>
                      </a>
                      <p class= "pubmed"> <?php echo "["
                      .$item -> get_pdbid().", "
                      .$item-> get_experimentalTechnique()."]";?>
                      </p>
                    </div>
                  </td>
                </tr>
              <?php endforeach;?>

               <tr>
                <td>see more...</td>
              </tr>
            </tbody>
          </table>
    </div>
  </div>
</div>
