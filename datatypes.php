<?php
/**
 * Created by PhpStorm.
 * User: rliu1
 * Date: 4/20/16
 * Time: 2:06 PM
 */

require_once dirname(__FILE__) . '/trackactivity.php';
include dirname(__FILE__) . '/views/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <strong>Protein Structure </strong>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td>RCSB Protein Data Bank</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <strong>Physiological Signals </strong>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td>PhysioBank</td>
                        </tr>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <strong>Morphology </strong>
                </div>
                <div class="panel-body">

                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td>NeuroMorpho.Org</td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <strong>Nucleotide Sequence </strong>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td>Sequence Read Archive</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>

    <div class="row">

        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <strong>Phenotype </strong>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td>dbGaP</td>
                        </tr>
                        <tr>
                            <td>Mouse Phenome Database </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <strong>Clinical Trials </strong>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td>ClinicalTrials</td>
                        </tr>
                        <tr>
                            <td>Clinical Trials Network</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <strong>Proteomics Data </strong>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td>PeptideAtlas</td>
                        </tr>
                        <tr>
                            <td>Proteomexchange </td>
                        </tr>
                        <tr>
                            <td>Yale Protein Expression Database </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>


        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <strong>Gene Expression </strong>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td>Gene Expression Omnibus</td>
                        </tr>
                        <tr>
                            <td>ArrayExpress </td>
                        </tr>
                        <tr>
                            <td>GEMMA </td>
                        </tr>
                        <tr>
                            <td>Nuclear Receptor Signaling Atlas </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>


    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <strong>Imaging Data </strong>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td>CardioVascular Research Grid</td>
                        </tr>
                        <tr>
                            <td>NeuroMorpho.Org </td>
                        </tr>
                        <tr>
                            <td>Clinical Trials Network</td>
                        </tr>
                        <tr>
                            <td>Cancer Imaging Archive</td>
                        </tr>
                        <tr>
                            <td>Open sharing of Functional Magnetic Resonance Imaging</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <strong>Unspecified </strong>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td>LINCS</td>
                        </tr>
                        <tr>
                            <td>BioProject </td>
                        </tr>
                        <tr>
                            <td>Dryad Digit Repository </td>
                        </tr>
                        <tr>
                            <td>Dataverse Network Project</td>
                        </tr>
                        <tr>
                            <td>NIDDK Central Repository </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>


    </div>
    </div>

<?php include dirname(__FILE__) . '/views/footer.php'; ?>
