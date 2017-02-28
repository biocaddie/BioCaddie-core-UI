<?php include 'gwas_header.php'; ?>
<br>
<div class="container">
    <div class="jumbotron background">
        <h1>BioCADDIE</h1>

        <p class="lead white">Engaging the community toward a Data Discovery Index</p>

        <div class="col-lg-12">
            <form action='gwas_result.php' method='get' autocomplete='off'>
                <div class="input-group">
                    <div class="col-lg-12">
                        <table>
                            <tr>
                                <td>
                                    <input type="text" class="form-control" placeholder="Search by traits"
                                           name='query1'>

                                    <p><font size="2">&nbsp &nbsp&nbsp Search examples(breast cancer, Alzheimer's
                                            disease, etc)</font></p></td>
                                <td>&nbsp &nbsp&nbsp&nbsp&nbsp&nbsp</td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Search by platforms"
                                           name='query2'>

                                    <p><font size="2">&nbsp &nbsp&nbsp Search examples(Illumina,Affymetrix, etc)</font>
                                    </p></td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" class="form-control" placeholder="Filter by case size"
                                           name='query3'>

                                    <p><font size="2">&nbsp &nbsp&nbsp Enter the minimum case size here</font></p></td>
                                <td>&nbsp &nbsp&nbsp&nbsp&nbsp&nbsp</td>
                                <td>

                                    <div class="input-group-btn">
                                        <!-- <button class="btn btn-default" type="button" value='submit'><a href="./result.htm">Search</a></button>-->
                                        <input class="btn-primary btn btn-default" type='submit' value='Search'>

                                        <p><font size="2">&nbsp &nbsp&nbsp </font></p>
                                    </div><!--input-group-btn-->
                                </td>
                            </tr>
                        </table>
                    </div>
                </div><!--input-group-->
            </form>
        </div><!-- /.col-lg-12 -->


    </div> <!-- jumbotron -->


    <div class="row marketing">
        <div class="col-lg-6">
            <h3>Getting Started</h3>

            <p><font size=4>Check out our comprehensive tutorials and materials.</font></p>

            <!--<h3><a href='datasource3.php'>Resources</a></h3>-->
            <h3>Resources</h3>

            <p><font size=4>See the list of resources BioCADDIE can harvest.</font></p>

            <h3>Features</h3>

            <p><font size=4>Features about BioCADDIE.</font></p>

        </div><!-- end col-lg-6 -->

        <div class="col-lg-6">
            <h3>Tools</h3>

            <p><font size=4>Browse our tools which can help you to access our system more easily.</font></p>

            <h3>Social Media</h3>

            <p><font size=4>Check out BioCADDIE in different social media channels.</font></p>

            <h4><br></h4>

            <p><br></p>
            <h4><br></h4>

            <p><br></p>
            <h4><br></h4>

            <p><br></p>
            <h4><br></h4>

            <p><br></p>
            <h4><br></h4>

            <p><br></p>
            <h4><br></h4>

            <p><br></p>
            <h4><br></h4>

            <p><br></p>
        </div><!-- end col-lg-6 -->
    </div><!-- row marketing -->
</div>
<?php include '../views/footer.php'; ?>
			
