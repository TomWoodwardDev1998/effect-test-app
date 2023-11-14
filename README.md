## This project

- A good way to reflect on this little project would be on my current lack of knowledge with AWS. The setup was time consuming for me and in the end an error surrounding the `startDocumentTextDetection` function DocumentLocation parameters has restricted me from making any further progress.
- The exposure has been a nice eye opener, as its allowed me to store files into an S3 bucket which is something I've nt done for a couple of years.

## The code

- The `UploadTest` feature tests mocks request to a route to try and upload a PDF to S3 and then extract the content.
- The tests checks to see if the content and time of the upload is recoreded into a pdf_uploads table `PDFUpload.php`.
- A test also checks for a null pdf value, this should be caught in a custom request file `StorePDFContentRequest.php` meaning the logic in the controller isn't hit without a file being present and of the correct format `ProcessPDFController.php`.
- The `ProcessPDFController` is a single action controller to try and follow the single responsibility principle

## Later Additions

- Considering the extraction was successful, the logic for it which is currently in the controller could be removed and encapsulated into a class of its own so the controller only ever reads the data, passes it to the class and returns a response.
- We could use the bridge pattern here and pass a service to the class defined in a config then based on which service has been passed in use their implementation for storing and extracting files.

- As we are interacting with an API, we may benefit from the use of a response handler class, this way we can wrap responses in appropriate formsts for the end user and handle errors without a user being greeted with a 500 error.

- If we were to go down the route of a class (ProcessPDF) for the file storage and extraction implementation we would require it to hold different properties and functions:
    - a file property
    - a file property could be split into path and name attributes
    - a service property - which could possibly accept a string of either `aws` or `azure`
    - a storeFileLocally() function
    - a storeFileToCloud() function
    - a extractFileContent() function

## Possible unit tests for ProcessPDF class

1. ProcessPDFTest.php
    A. can_get_a_file_name_attribute - assert matches string
    B. can_get_a_file_path_attribute - assert matches string
    C. cannot_get_a_file_name_attribute_if_file_is_incorrect_type - assert matches string
    D. cannot_get_a_file_path_attribute_if_file_does_not_exist - assert matches string
    E. can_get_aws_implementation_when_set_in_project_config - assert true for expected cloud service
    F. cannot_get_aws_implementation_when_alternative_service_is_set_in_project_config - assert false
    G. can_get_file_instance_from_locally_store_file - assert true on file_exists()
    H. can_get_cloud_service_cloud_instance - assert file with filename is returned which matches local version
    I. can_get_extracted_content_from_pdf_file - assert matches string