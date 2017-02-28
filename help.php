<?php
require_once 'Model/TrackActivity.php';
?>

<?php include 'views/header.php'; ?>

<script>
    $(function () {
        var $sidebar = $("#sidebar"),
            $window = $(window),
            offset = $sidebar.offset(),
            topPadding = 15;

        $window.scroll(function () {
            if ($window.scrollTop() > offset.top) {
                $sidebar.stop().animate({
                    marginTop: $window.scrollTop() - offset.top + topPadding
                });
            } else {
                $sidebar.stop().animate({
                    marginTop: 0
                });
            }
        });
    });

    $(document).ready(function () {
        $('[data-toggle=offcanvas]').click(function () {
            $('.row-offcanvas').toggleClass('active');
        });
    });
</script>

<div class="page-container" style="margin-bottom: 50px">

    <div class="container">
        <div class="row row-offcanvas row-offcanvas-left">

            <!-- sidebar -->
            <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
                <ul class="nav">
                    <li class="active"><a href="#"><i class="glyphicon glyphicon-home"></i> Overview</a></li>
                    <li><a href="#" data-toggle="collapse" data-target="#sub1"><i class="glyphicon glyphicon-plus"> </i>
                            Boolean Logic</a>
                        <ul class="nav collapse" id="sub1" style="margin-left: 15px">
                            <li><a href="#intro">Introduction</a></li>
                            <li><a href="#and">AND</a></li>
                            <li><a href="#or">OR</a></li>
                            <li><a href="#not">NOT</a></li>
                            <li><a href="#nesting">Nesting</a></li>
                        </ul>
                    </li>
                    <li><a href="#field"><i class="glyphicon glyphicon-search"> </i> Search by Field</a></li>
                </ul>
            </div>

            <!-- main area -->
            <div class="col-xs-12 col-sm-9">
                <div id="main">
                    <h1>DataMed Tutorial</h1>
                    <hr>
                    <h3>Overview</h3>
                    <p>
                        According to Wikipedia, “In golf, a caddy or caddie is the person who carries a player's bag and
                        clubs, and gives insightful advice and moral support. A good caddy is aware of the challenges
                        and obstacles of the golf course being played, along with the best strategy in playing it.” This
                        quote is emblematic of this project because bioCADDIE is designed to help data producers,
                        disseminators, and consumers “play their game” in the new era of team science. It will give
                        players insightful advice and financial support to implement new ideas.
                    </p>
                    <p>
                        The bioCADDIE team will develop a data discovery index (DDI) prototype which will index data
                        that are stored elsewhere. The DDI will play an important role in promoting data integration
                        through the adoption of content standards and alignment to common data elements and high-level
                        schema. A DDI could provide the means to test the utility of these standards. It could serve as
                        an incubator to spur the types of quality metrics that currently are being developed around
                        article metrics, including citation analysis and altmetrics.

                    </p>

                </div>

                <div id="intro">
                    <h1>Introduction to Boolean Logic</h1>
                    <hr>
                    <p>
                        Boolean logic is a system of showing relationships between sets by using the words "AND," "OR,"
                        and "NOT." (The term Boolean comes from the name of the man who invented this system, George
                        Boole.) Boolean logic is recognized by many electronic searching tools as a way of defining a
                        search string.
                    </p>
                    <ul>
                        <li>The Boolean operators AND, OR, NOT can be used to combine search terms in bioCADDIE.</li>
                        <li>In bioCADDIE, Boolean operators <strong>must be entered in uppercase letters.</strong></li>
                    </ul>


                </div>
                <div id="and">
                    <h3>AND</h3>
                    <ul>
                        <li>Used to retrieve a set in which each data set contains all search terms.</li>
                    </ul>
                    <pre><p>Example: Skin AND Cancer</p></pre>

                    <img src="./img/help/and.jpg" alt="bioCADDIE" style="height: 180px;margin: 10px">

                </div>
                <div id="or">
                    <h3>OR</h3>
                    <ul>
                        <li>Used to retrieve a set in which each citation contains at least one of the search terms.
                        </li>
                        <li>Use OR when you want to pull together articles on similar topics.</li>
                    </ul>
                    <pre><p>Example: Lung OR Cancer</p></pre>
                    <img src="./img/help/or.jpg" alt="bioCADDIE" style="height: 160px;margin: 10px">

                </div>
                <div id="not">
                    <h3>NOT</h3>
                    <ul>
                        <li>Retrieves a set from which citations to articles containing specified search terms following
                            the NOT operator are eliminated.
                        </li>
                    </ul>
                    <pre><p>Example: Lung NOT Cancer</p></pre>
                    <img src="./img/help/not.jpg" alt="bioCADDIE" style="height: 200px;margin: 10px">

                </div>
                <div id="nesting">
                    <h3>Nesting</h3>
                    <ul>
                        <li>When using multiple Boolean operators in bioCADDIE, they are processed left to right.</li>
                    </ul>
                    <pre><p>Example: Cancer AND Lung OR Breast</p></pre>

                    <p> This will retrieve records that include both terms Cancer AND Lung as well as all
                        records with the term Breast, whether or not they contain the other two terms.</p>

                    <ul>
                        <li>
                            To change the order in which terms are processed, enclose the terms(s) in parentheses. The
                            terms inside the set of parentheses will be processed as a unit and then incorporated into
                            the overall strategy. This is called nesting.
                        </li>
                    </ul>
                    <pre><p>Example: Cancer AND (Lung OR Breast)</p></pre>
                    <p>
                        This will retrieve records that contain the term Cancer, as well as one or both of the terms
                        Lung OR Breast.
                    </p>

                </div>

                <div id="field">
                    <h1>Search by Field</h1>
                    <hr>
                    <p>Terms may be qualified using bioCADDIE's search field tags. The allowable fields are: title, author and description.</p>

                    <p>The search field tag must follow the term -- you cannot prequalify.</p>

                    <p> Tags are enclosed in square brackets.</p>

                    <pre><p>Example: Lung[title] AND Cancer</p></pre>

                    Reminders:
                     <ul>

                       <li> Boolean operators -- AND, OR, NOT -- must be entered in uppercase letters.</li>
                         <li> Boolean operators are processed from left to right.</li>
                         <li> Use parentheses to nest terms together so they will be processed as a unit and then incorporated
                        into the overall strategy.</li>

                       </ul>
                    </p>

                </div>
            </div><!-- /.col-xs-12 main -->
        </div><!--/.row-->
    </div><!--/.container-->
</div><!--/.page-container-->


<?php include 'views/footer.php'; ?>
