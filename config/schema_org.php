
<?php
/*
$str='{
    "properties": {

        "@type": { "type": "string", "enum": [ "Dataset"] },
        "identifier": {
            "$ref": "identifier_info_schema.json#"
        },
        "alternateIdentifiers": {
            "description": "Alternate identifiers for the dataset.",
            "type": "array",
            "items": {
                "$ref": "alternate_identifier_info_schema.json#"
            }
        },
        "relatedIdentifiers": {
            "description": "Related identifiers for the dataset.",
            "type": "array",
            "items": {
                "$ref": "related_identifier_info_schema.json#"
            }
        },
        "title" : {
            "description" : "The name of the dataset, usually one sentece or short description of the dataset.",
            "type" : "string"
        },
        "description" : {
            "description": "A textual narrative comprised of one or more statements describing the dataset.",
            "type" : "string"
        },
        "dates" : {
            "description": "Relevant dates for the datasets, a date must be added, e.g. creation date or last modification date should be added.",
            "type" : "array",
            "items" : {
                "$ref" : "date_info_schema.json#"
            }
        },
        "types" : {
            "description": "A term, ideally from a controlled terminology, identifying the dataset type or nature of the data, placing it in a typology",
            "type" : "array",
            "items" : {
                "$ref" : "data_type_schema.json#"
            },
            "minItems" : 1
        },
        "availability": {
            "description": "A qualifier indicating the different types of availability for a dataset (available, unavailable, embargoed, available with restriction, information not available).",
            "type": "string"
        },
        "refinement": {
            "description": "A qualifier to describe the level of data processing of the dataset and its distributions.",
            "type": "string"
        },
        "aggregation": {
            "description": "A qualifier indicating if the entity represents an \'instance of dataset\' or a \'collection of datasets\'.",
            "type": "string"
        },
        "privacy": {
            "description": "A qualifier to describe the data protection applied to the dataset. This is relevant for clinical data.",
            "type": "string"
        },
        "distributions" : {
            "description": "The distribution(s) by which datasets are made available (for example: mySQL dump).",
            "type" : "array",
            "items" : {
                "$ref" : "dataset_distribution_schema.json#"
            }
        },
        "dimensions" : {
            "description": "The different dimensions (granular components)  making up a dataset.",
            "type" : "array",
            "items" : {
                "$ref" : "dimension_schema.json#"
            }
        },
        "primaryPublications" : {
            "description": "The primary publication(s) associated with the dataset, usually describing how the dataset was produced.",
            "type" : "array",
            "items" : {
                "$ref" : "publication_schema.json#"
            }
        },
        "citations" : {
            "description": "The publication(s) that cite this dataset.",
            "type" : "array",
            "items" : {
                "$ref" : "publication_schema.json#"
            }
        },
        "citationCount": {
            "description": "The number of publications that cite this dataset (enumerated in the citations property)",
            "type": "integer"
        },
        "producedBy" : {
            "description": "A study process which generated a given dataset, if any.",
            "$ref" : "study_schema.json#"
        },
        "creators" : {
            "description": "The person(s) or organization(s) which contributed to the creation of the dataset.",
            "type" : "array",
            "items" : {
                "oneOf": [
                    {"$ref" : "person_schema.json#"},
                    {"$ref" : "organization_schema.json#"}
                ]
            },
            "minItems" : 1
        },
        "licenses": {
            "description": "The terms of use of the dataset.",
            "type": "array",
            "items": {
                "$ref": "license_schema.json#"
            }
        },
        "isAbout": {
            "description" : "Different entities (biological entity, taxonomic information, disease, molecular entity, anatomical part, treatment) associated with this dataset.",
            "type": "array",
            "items": {
                "anyOf": [
                    {"$ref" : "biological_entity_schema.json#"},
                    {"$ref" : "taxonomic_info_schema.json#"},
                    {"$ref" : "disease_schema.json#"},
                    {"$ref" : "molecular_entity_schema.json#"},
                    {"$ref" : "anatomical_part_schema.json#"},
                    {"$ref" : "treatment_schema.json#"}
                ]
            }
        },
        "hasPart" : {
            "description": "A Dataset that is a subset of this Dataset; Datasets declaring the \'hasPart\' relationship are considered a collection of Datasets, the aggregation criteria could be included in the \'description\' field.",
            "type": "array",
            "items": {
                "$ref" : "dataset_schema.json#"
            }
        },
        "acknowledges" : {
            "description": "The grant(s) which funded and supported the work reported by the dataset.",
            "type" : "array",
            "items" : {
                "$ref" : "grant_schema.json#"
            }
        },
        "keywords" : {
            "description": "Tags associated with the dataset, which will help in its discovery.",
            "type": "array",
            "items": {
                "$ref" : "annotation_schema.json#"
            }
        },
        "extraProperties": {
            "description": "Extra properties that do not fit in the previous specified attributes. ",
            "type": "array",
            "items": {
                "$ref" : "category_values_pair_schema.json#"
            }
        }
    },
    "additionalProperties": false,
    "required" : [ "title", "types", "creators" ]
}';
*/
//$t= json_decode($str,true);

//$n=$t['properties'];
//var_dump($n);

$dataset_array=array(
		"distribution", 
		"includedInDataCatalog",
		"about",
		"accessMode",
		"accessModeSufficient",
		"accessibilityAPI",
		"accessibilityControl",
		"accessibilityFeature",
		"accessibilityHazard",
		"accessibilitySummary",
		"accountablePerson",
		"aggregateRating",
		"alternativeHeadline",
		"associatedMedia",
		"audience",
		"audio",
		"author",
		"award",
		"character",
		"citation",
		"comment",
		"commentCount",
		"contentLocation",
		"contentRating",
		"contributor",
		"copyrightHolder",
		"copyrightYear",
		"creator",
		"dateCreated", 
		"dateModified",
		"datePublished",
		"discussionUrl",
		"editor",
		"educationalAlignment",
		"educationalUse",
		"encoding",
		"exampleOfWork",
		"fileFormat",
		"funder",
		"genre",
		"hasPart",
		"headline",
		"inLanguage",
		"interactionStatistic",
		"interactivityType",
		"isAccessibleForFree",
		"isBasedOn",
		"isFamilyFriendly",
		"isPartOf",
		"keywords",
		"learningResourceType",
		"license",
		"locationCreated",
		"mainEntity",
		"material",
		"mentions",
		"offers",
		"position",
		"producer",
		"provider",
		"publication",
		"publisher",
		"publishingPrinciples",
		"recordedAt",
		"releasedEvent",
		"review",
		"schemaVersion",
		"sourceOrganization",
		"spatialCoverage",
		"sponsor",
		"temporalCoverage",
		"text",
		"thumbnailUrl",
		"timeRequired",
		"translator",
		"typicalAgeRange",
		"version",
		"video",
		"workExample",
		"additionalType",
		"alternateName",
		"description",
		"disambiguatingDescription",
		"identifier",
		"image",
		"mainEntityOfPage",
		"name",
		"potentialAction",
		"sameAs",
		"url"
);

$mapping=array(
		"identifier"=>"identifier",
		"alternateIdentifiers"=>"identifier",
		"relatedIdentifiers"=>"",
		"title"=>"name",
		"types"=>"additionalType",
		"creators"=>"creator",
		"licenses"=>"license",
		"spatialCoverage"=>"spatialCoverage",
		"distributions"=>"distribution",
		"description"=>"description",
		"storedIn"=>"includedInDataCatalog",
		"dimensions"=>"variableMeasured",
		"primaryPublications"=>"citation",
		"producedBy"=>"producer",
		"citations"=>"citation",
		"isAbout"=>"about",
		"hasPart"=>"hasPart",
		"keywords"=>"keywords",
		"acknowledges"=>"funder",
		"dates"=>"datePublished",
		"version"=>"version",
		"access"=>"accessibilityAPI",
		"formats"=>"fileFormat",
		"size"=>"contentSize",
		"unit"=>"unitText"		
);


?>