<?php

// Questa include è sempre corretta
include_once(dirname(__FILE__)."/include_globals.php");

// Includo la classe con la pagina base
if(!class_exists("clsUIPageBase")) include_once($_folderPhpCore."clsUIPageBase.php");
// Includo la classe di errore Garavot
if(!class_exists("clsError")) include_once($_folderPhpCore."clsError.php");
if(!class_exists("clsManageGXML")) include_once($_folderLibPhp."clsManageGXML.php");
if(!class_exists("clsManageXMLConfig")) include_once($_folderCorePhp."clsManageXMLConfig.php");

DEFINE('CONST_STALKER_STB_NAME'   ,'MAG250',FALSE);
DEFINE('CONST_STALKER_STB_ID'     ,'4'     ,FALSE);
DEFINE('CONST_STALKER_MODEL_NAME' ,'MAG250',FALSE);
DEFINE('CONST_STB_MODEL_MAG250_ID','4'     ,FALSE);
class clsDALSTBEquipments extends clsUIPageBase{
    private   $_methodName = "";
    private   $_className  = "";
    protected $_entities   = 'Categories';
    
    private $_pageStalker = 'stalker_rest_api.php';
    
    public function __construct(){
        parent::__construct("6.2");
        $this->SetCurrentModuleName("clsDALSTBEquipments");
        $this->_className = $this->GetCurrentModuleName().".";
        $this->RetrieveTraslations();
    }
    
    public function GetDataToManageEquipment($idResource){
        $this->_methodName = __METHOD__;
        $spName            = "sp_ManageEquipment";
        
        if($idResource >0)
            $op = 2;
        else
            $op = 1;
        
        $spPars
            = $this->GetIdCurrentLanguage() //p_IDLanguage
            . ", ".$op //p_Option
            . ", ".$idResource //p_FKResource
            . ", 0"//p_FKUser
            . ", 0"//p_FKModel
            . ", ''" //p_Name
            . ", ''" //p_Identifier
            . ", 0" //p_LTVPrevPosX
            . ", 0" //p_LTVPrevPosY
            . ", 0" //p_LTVPrevScale
            . ", 0" //p_LTVPrevState
            . ", 0" //p_VODPrevPosX
            . ", 0" //p_VODPrevPosY
            . ", 0" //p_VODPrevScale
            . ", 0" //p_VODPrevState
            . ", 0" //p_FKLanguage
            . ", ''" //p_WelcomeMessage
            . ", 0" //p_ManagePin
            . ", 0" //p_InfoHotel
            . ", 0" //p_ParentalControl
            . ", 0" //p_PurchasingManagement
            . ", ''" //p_IPAddress
            . ", 0" //p_Port
            . ", ''" //p_MAC
        ;
        $result = $this->ExecuteStoredProcedureMultiDataset($spName, $spPars);
        
        $errorNumber = $this->GetErrorNumber();
        if ($errorNumber!="0" && $errorNumber!=""){
            $gErr = new clsError();
            $gErr->setOrigin($this->_className.$this->_methodName);
            $gErr->setNumber($errorNumber);
            $gErr->setMessage($this->GetErrorMessage());
            $gErr->setTitle("Error");
            $gErr->setDetails($this->GetErrorDetails());
            $ret = array("success"=>false, "errors"=>true, "values"=>$gErr);
        }else{
            $ret = $result;
        }
        
        return $ret;
    }
    
    public function GetListForGrid($start, $limit, $sort, $dir, $gridID){
        $this->_methodName = __METHOD__;
        if ($dir == null)
            $dir ="";
        
        if ($sort=="STBName") $sort = "t_Resources.Name";
        if ($sort=="ModelName") $sort = "t_STBModels.Name";
        if ($sort=="LanguageName") $sort = "t_Languages.Name";
        
        $spName = "sp_STBGetListOfEquipments";
        $spPars
            = $start
            . ", ".$limit
            . ", '".$sort." ".$dir."'"
        ;
        
        $result      = $this->ExecuteStoredProcedure($spName, $spPars);
        $errorNumber = $this->GetErrorNumber();
        if ($errorNumber!="0" && $errorNumber!=""){
            $gErr = new clsError();
            $gErr->setOrigin($this->_className.$this->_methodName);
            $gErr->setNumber($errorNumber);
            $gErr->setMessage($this->GetErrorMessage());
            $gErr->setTitle("Error");
            $gErr->setDetails($this->GetErrorDetails());
            $ret = array("errors"=>true, "values"=>$gErr);
            $ret = json_encode($ret);
        }else{
            $rows = 0;
            
            global $_localImgSystem;
            global $_imgModify, $_imgDelete, $_imgDetails; //, $_imgManage;
            
            $deleteUrlImg  = $_localImgSystem.$_imgDelete;
            $editUrlImg    = $_localImgSystem.$_imgModify;
            // $manageUrlImg = $_localImgSystem.$_imgManage;
            $detailsUrlImg = $_localImgSystem.$_imgDetails;
            // $editUrlImg = "'/Garavot2008/images/custom/edit-16x16.gif'";
            // $deleteUrlImg = "'/Garavot2008/images/custom/delete-16x16.gif'";
            
            while ($row = $result->fetch_assoc()){
                $detailsCommand = "_phpObjects['$gridID'].Details(".$row['IDResource'].");";
                // $manageCommand = "_phpObjects['$gridID'].Manage(".$row['IDResource'].");";
                $editCommand    = "_phpObjects['$gridID'].Edit(".$row['IDResource'].");";
                $deleteCommand  = "_phpObjects['$gridID'].Delete(".$row['IDResource'].");";
                
                $stbGroup = "";
                if ($rows == 0)
                    $rows = $row['TotRows'];
                
                if ($row['IPAddress'] != null)
                    $IPAddress = $row['IPAddress'];
                else
                    $IPAddress = "DHCP";
                
                $item = Array(
                    "FKResource"        => $row['IDResource']
                    , "Identifier"      => $row['Identifier']
                    , "STBName"         => $row['STBName']
                    , "ModelName"       => $row['ModelName']
                    , "LanguageName"    => $row['LanguageName']
                    , "MustLogin"       => $row['MustLogin']
                    , "UserName"        => $row['UserName']
                    , "Details"         => "<img class='grid-active-link' src=$detailsUrlImg onclick=$detailsCommand />"
                    //, "Manage"     => "<img class='grid-active-link' src=$manageUrlImg onclick=$manageCommand />"
                    , "Edit"            => "<img class='grid-active-link' src=$editUrlImg onclick=$editCommand />"
                    , "Delete"          => "<img class='grid-active-link' src=$deleteUrlImg onclick=$deleteCommand />"
                    , "IPAddress"       => $IPAddress
                    , "MAC"             => $row['MAC']
                );
                
                $itemList[] = $item;
            }
            if($itemList=="")
                $itemList="";
            $ret = json_encode($itemList);
            $ret = '{"rows":"'.$rows.'","data":'. $ret.'}';
            
            $result->close();
        }
        
        return $ret;
    }
    
    public function ManageEquipment($pars){
        GLOBAL $_folderLibPhp;
        
        $this->_methodName = __METHOD__;
        $spName            = "sp_ManageEquipment";
        $STALKER           = FALSE;
        
        $op         = $pars['hValOperation'];
        $idResource = $pars['hValFKResource'];
        
        IF($pars['cboModel']==CONST_STALKER_MODEL_NAME){
            $STALKER = TRUE;
            $fkModel = 0;
        }ELSE{
            $fkModel = $pars['cboModel'];
        }
        $fkUser     = $pars['cboUser'];
        $name       = $pars['txtName'];
        $identifier = $pars['txtIdentifier'];
        
        $managePin            = $pars['txtManagePin'];
        $infoHotel            = $pars['rdgInfoHotel'];
        $parentalControl      = $pars['rdgParentalControl'];
        $purchasingManagement = $pars['rdgPurchasingManagement'];
        IF($managePin=="")$managePin=0;
        
        $ltvPrevPosX  = $pars['ltvPrevPosX'];
        $ltvPrevPosY  = $pars['ltvPrevPosY'];
        $ltvPrevScale = $pars['ltvPrevScale'];
        $ltvPrevState = $pars['ltvPrevState'];
        
        $vodPrevPosX  = $pars['vodPrevPosX'];
        $vodPrevPosY  = $pars['vodPrevPosY'];
        $vodPrevScale = $pars['vodPrevScale'];
        $vodPrevState = $pars['vodPrevState'];
        
        $fkLanguage = $pars['cboLanguage'];
        
        $IPAddress = $pars['txtIPAddress'];
        $Port      = $pars['txtPort'];
        $STB_MAC   = $pars['txtMAC'];
        
        $spPars
            = $this->GetIdCurrentLanguage() //p_IDLanguage
            . ", ".$op                      //p_Option
            . ", ".$idResource              //p_FKResource
            . ", ".$fkUser                  //p_FKUser
            . ", ".$fkModel                 //p_FKModel
            . ", '$name'"                   //p_Name
            . ", '$identifier'"             //p_Identifier
            . ", ".$ltvPrevPosX             //p_LTVPrevPosX
            . ", ".$ltvPrevPosY             //p_LTVPrevPosY
            . ", ".$ltvPrevScale            //p_LTVPrevScale
            . ", ".$ltvPrevState            //p_LTVPrevState
            . ", ".$vodPrevPosX             //p_VODPrevPosX
            . ", ".$vodPrevPosY             //p_VODPrevPosY
            . ", ".$vodPrevScale            //p_VODPrevScale
            . ", ".$vodPrevState            //p_VODPrevState
            . ", ".$fkLanguage              //p_FKLanguage
            . ", '{$pars['txtWelcomeMessage']}'" //p_FKLanguage
            . ", ".$managePin.""            //p_ManagePin
            . ", ".$infoHotel               //p_InfoHotel
            . ", ".$parentalControl         //p_ParentalControl
            . ", ".$purchasingManagement    //p_PurchasingManagement
            . ", '$IPAddress'"              //p_PurchasingManagement
            . ", ".$Port                    //p_PurchasingManagement
            . ", '$STB_MAC'"                 //p_MAC
        ;
        
        $this->StartTransaction();
        $result = $this->ExecuteStoredProcedure($spName, $spPars);
        
        if ($this->GetErrorNumber()!=0){
            $this->RollbackTransaction();
            $ret = array("success"=>false, "errors"=>true, "values"=>$this->RetrieveError($this->GetErrorNumber()));
            return $ret;
        }else{
            $row       = $result->fetch_assoc();
            //$idAccount = 0;
            $idGroup   = $row['IDGroup'];
            
            if($idGroup>0){
                $spName = "sp_DeleteAssignedGroupsResources";
                $spPars = " ".$idGroup ;
                $this->ExecuteStoredProcedure($spName, $spPars);
                if ($this->GetErrorNumber()!=0){
                    $this->RollbackTransaction();
                    $ret = array("success"=>false, "errors"=>true, "values"=>$this->RetrieveError($this->GetErrorNumber()));
                    return $ret;
                }
                
                // ??? $resourcesAssigned -- senza quella variabile il resto del blocco è inutile 2014/09/29
                
                //$arrResources = split("[|]",$resourcesAssigned);
                //$spName       = "sp_AssignGroupToGroupsResources";
                //$spPars       = "";
                //$num          = count($arrResources);
                //if($arrResources[0] != ""){
                //    for($i=0;$i<$num;$i++){
                //        $spPars  = " ".$idGroup ; //p_IdLoginAccount
                //        $spPars .= ", ".$arrResources[$i].""; //p_IdLoginGroup
                
                //        $this->ExecuteStoredProcedure($spName, $spPars);
                //        if ($this->GetErrorNumber()!="0" && $this->GetErrorNumber()!=""){
                //            $this->RollbackTransaction();
                //            $ret = array("success"=>false, "errors"=>true, "values"=>$this->RetrieveError($this->GetErrorNumber()));
                //            return $ret;
                //        }
                //    }
                //}
            }
            $this->CommitTransaction();
            $ret = array("success"=>true, "errors"=>false, "values"=>$idGroup);
        }
        
        // dirotta su stalker
        IF($STALKER && $op===5){
            IF(!class_exists('stalker_api')) INCLUDE_ONCE($_folderLibPhp.$this->_pageStalker);
            $stalker = NEW stalker_api();
            //$the_mac=stalker_MAC_from_SN($identifier);
            $stalker->rest_stb_delete($STB_MAC);
        }
        
        return $ret;
    }
    
    private function RetrieveError($errorNumber){
        $gErr = new clsError();
        $gErr->setOrigin($this->_className.$this->_methodName);
        $gErr->setNumber($errorNumber);
        $gErr->setMessage($this->GetErrorMessage());
        $gErr->setTitle("Error");
        $gErr->setDetails($this->GetErrorDetails());
        return $gErr;
    }
    
    // Interfaccio il decoder tramite il servizio per recuperare i dati da visualizzare e per settare quelli necessari
    public function GetAndSetSTBDataUsingGService($ipaddress, $port, $rank, $idApplication){
        $this->_methodName   = "GetAndSetSTBDataUsingGService";
        $objManageXMLConfig  = new clsManageXMLConfig();
        $outputpath          = $objManageXMLConfig->GETOutputFolder();
        $objManageGxml       = new clsManageGXML($idApplication, $objManageXMLConfig);
        $arrWork             = $objManageXMLConfig->GetWorkflowsFromApplication($idApplication);
        $fileNameTMP         = $idApplication . "_" . date("Ymd").date("His") . $this->_LoginUserData->GetUserID();
        
        for ($i=0; $i < count($arrWork);$i++){
            switch($arrWork[$i]->id){
                case "WorkflowManageSTB":
                    $objManageGxml->DoDefaultWorkflow($arrWork[$i], $arrWork);
                    //$objManageGxml->AddGlobalGaravotParam("idasset",$idAsset);
                    //$objManageGxml->AddGlobalParam("outputfilename",$fileNameTMP . ".txt");
                    $objManageGxml->CloseDoc();
                    $objManageXMLConfig->AddParamsNodesToGxml($objManageGxml->xmlDoc,$arrWork[$i]->params,"/root/workflow[@id='" . $arrWork[$i]->id ."'][@rank='" . $arrWork[$i]->rank ."']");
                    if ($idApplication == "WebSTBSetDefaultChannel")
                        $objManageGxml->AddParamToWorkflow($arrWork[$i]->id,$arrWork[$i]->rank,"idchannel", $rank, "/params/methodparams");
                    $objManageGxml->AddParamToWorkflow($arrWork[$i]->id,$arrWork[$i]->rank,"ipaddress", $ipaddress, "/params/stb");
                    $objManageGxml->ReplaceParamToWorkflow($arrWork[$i]->id,$arrWork[$i]->rank,"port", $port, "/params/stb");
                    $objManageGxml->AddParamToWorkflow($arrWork[$i]->id,$arrWork[$i]->rank,"responsefile",$fileNameTMP .".xml", "/params");
                    break;
                default:
                    $objManageGxml->DoDefaultWorkflow($arrWork[$i], $arrWork);
                    $objManageXMLConfig->AddParamsNodesToGxml($objManageGxml->xmlDoc,$arrWork[$i]->params,"/root/workflow[@id='" . $arrWork[$i]->id ."'][@rank='" . $arrWork[$i]->rank ."']");
                    break;
            }
        }
        
        $GXMLPath = $objManageXMLConfig->GETGXMLpath();
        $objManageGxml->xmlDoc->save($GXMLPath . $fileNameTMP.".xml");
        unset ($objManageGxml->xmlDoc);
        unset ($objManageGxml);
        rename($GXMLPath .$fileNameTMP.".xml", $GXMLPath .$fileNameTMP.".gxml");
        
        $millTimeout = (int) $objManageXMLConfig->GetWorkflowParamApplication($idApplication, "WorkflowManageSTB",  "1", "timeout");
        $Timeout     = $millTimeout / 1000;
        
        for ($i=0; $i < $Timeout; $i++){
            if(file_exists($outputpath . $fileNameTMP. ".xml")){
                $xmlDoc = new DOMDocument();
                $xmlDoc->load($outputpath . $fileNameTMP. ".xml");
                $xpath = new DOMXPath($xmlDoc);
                
                $xQuery = "/root/result";
                $DOMNodeList = $xpath->query($xQuery);
                $result = $DOMNodeList->item(0)->textContent;
                
                $xQuery = "/root/message";
                $DOMNodeList = $xpath->query($xQuery);
                $message = $DOMNodeList->item(0)->textContent;
                
                if ($result == "0")
                    $ret = array("success"=>false, "errors"=>true, "values"=>array("Title"=>"Error in GService", "Number"=>$result, "Message"=>"Error in GService Workflows" , "Details"=>$message), "check"=>0);
                else
                    $ret = array("success"=>true, "errors"=>false, "values"=>$message, "check"=>1);
                return ($ret);
            }
            sleep(1);
        }
        
        $ret = array("success"=>false, "errors"=>true, "values"=>array("Title"=>"Errore in lettura valore di ritorno servizio di cancellazione risorsa", "Number"=>-1, "Message"=>"Timeout nella lettura dati device" , "Details"=>"GSERVICE TIMEOUT"));
        return $ret;
    }
    
    public function GetWindowDetailsHTML($id){
        GLOBAL $_folderLibPhp;
        
        $this->_methodName = __METHOD__;
        $objID             = "winDetails".$this->_entities; // -> _idGrid
        $result            = $this->GetDataToManageEquipment($id);
        $Name           = "";
        $Identifier     = "";
        $STBModelIndex  = "";
        $LanguageIndex  = "";
        $STBModel       = "";
        $Language       = "";
        $HWVersion      = "";
        $SWVersion      = "";
        
        $data           = $result[3];
        
        IF($row = $data->fetch_assoc()){
            $Name           = $row['Name'];
            $Identifier     = $row['Identifier'];
            $STBModelIndex  = $row['FKSTBModel'];
            $LanguageIndex  = $row['FKLanguage'];
            IF ($row['IPAddress'] != NULL)
                $IPAddress = $row['IPAddress'];
            ELSE
                $IPAddress = "DHCP";
            
            IF($STBModelIndex == CONST_STB_MODEL_MAG250_ID){
                IF(!class_exists('stalker_api')) INCLUDE_ONCE($_folderLibPhp.$this->_pageStalker);
                $stalker = NEW stalker_api();
                
                $stb_mac = $row['MAC']; //stalker_MAC_from_SN($Identifier);
                $mag     = $stalker->rest_stb_single($stb_mac);
                
                IF($mag===FALSE || $mag->status!='OK'){
                    $Status    = "<span style='color:red'>&lt;Err ({$stalker->__stalker_last_err})&gt;</span>";
                    $HWVersion = 0;
                    $SWVersion = 0;
                }ELSE{
                    $mag       = $mag->results[0]->online;
                    IF($mag==='0'){
                        $mag = FALSE;
                        IF($IPAddress!='DHCP')
                            $mag = $stalker->mag_ping($IPAddress);
                        IF($mag!==FALSE)
                            $Status = "<span style='color:orange'>Ping</span>";
                        ELSE
                            $Status = "<span style='color:red'>Down</span>";
                    }ELSE{
                        $Status = "<span style='color:green'>Up</span>";
                    }
                    $mag_ver   = $stalker->db_mag_versions($Identifier);
                    $HWVersion = $mag_ver[1];
                    $SWVersion = $mag_ver[0];
                }
            }ELSE
                if ($row['IPAddress'] != null){
                    // Recupero Hardware e Software Version tramite il servizio
                    $res = $this->GetAndSetSTBDataUsingGService($row['IPAddress'], $row['Port'], 0, 'WebSTBManageHWInfo');
                    if ($res['values']['Number'] == -1 || $res['check'] == 0)
                        $HWVersion = 'Not Available';
                    else
                        $HWVersion = $res['values'];
                    
                    $res = $this->GetAndSetSTBDataUsingGService($row['IPAddress'], $row['Port'], 0, 'WebSTBManageSWInfo');
                    if ($res['values']['Number'] == -1 || $res['check'] == 0) {
                        $SWVersion = "Not Available";
                        $Status    = "<span style='color:red'>Down</span>";
                    }else{
                        $SWVersion = $res["values"];
                        $Status    = "<span style='color:green'>Up</span>";
                    }
                    // Fine Recupero Versioni
                }else{
                    $HWVersion = "Not Available";
                    $SWVersion = "Not Available";
                    $Status    = "Not Available";
                }
        }
        
        $data = $result[1];
        
        $strArrSTBModels = "";
        while($row = $data->fetch_assoc()){
            if($strArrSTBModels!="")
                $strArrSTBModels .=",";
            else
                $STBModel = $row['IDSTBModel'];
            $strArrSTBModels .= "['".$row['IDSTBModel']."', '".$row['Name']."']";
            if ($row['IDSTBModel'] == $STBModelIndex)
                $STBModel = $row['Name'];
        }
        if ($strArrSTBModels != "")
            $strArrSTBModels = "[$strArrSTBModels]";
        
        $data = $result[2];
        $strArrLanguages='';
        while($row = $data->fetch_assoc()){
            if($strArrLanguages!="")
                $strArrLanguages .=",";
            else
                $FKLanguage = $row['IDLanguage'];
            $strArrLanguages .= "['".$row['IDLanguage']."', '".$row['Name']."']";
            if ($row['IDLanguage'] == $LanguageIndex)
                $Language = $row['Name'];
        }
        $strArrLanguages = "[$strArrLanguages]";
        
        $html_generic = "<br><div style='padding-left:10px;font-size:12px;color:#15428B;font-weight:bold'>".
            $this->GetTranslation($objID.".lblDetails","Informazioni di base apparato").
            "</div><br>";
        
        $html_generic .= "<table class='detail-of-gvod-asset' cellspacing='0px'>";
        
        $html_generic .= $this->GetHtmlDetailsRow(0, $this->GetTranslation(
                $objID.".lblDetailsName","Nome").":", $Name);
        $html_generic .= $this->GetHtmlDetailsRow(1, $this->GetTranslation(
                $objID.".lblDetailsIdentifier","Identificativo").":", $Identifier);
        $html_generic .= $this->GetHtmlDetailsRow(0, $this->GetTranslation(
                $objID.".lblDetailsSTBModel","Modello").":", $STBModel);
        $html_generic .= $this->GetHtmlDetailsRow(1, $this->GetTranslation(
                $objID.".lblDetailsSTBLanguage","Lingua").":", $Language);
        $html_generic .= $this->GetHtmlDetailsRow(0, $this->GetTranslation(
                $objID.".lblDetailsIPAddress","IP Address").":", $IPAddress);
        $html_generic .= $this->GetHtmlDetailsRow(1, $this->GetTranslation(
                $objID.".lblDetailsHWVersion","Versione Hardware").":", $HWVersion);
        $html_generic .= $this->GetHtmlDetailsRow(0, $this->GetTranslation(
                $objID.".lblDetailsSWVersion","Versione Software").":", $SWVersion);
        $html_generic .= $this->GetHtmlDetailsRow(1, $this->GetTranslation(
                $objID.".lblDetailsStatus","Stato").":", $Status);
        
        $html_generic .= "</table><br>";
        
        return $html_generic;
    }
    
    public function GetManageData($idRecord){
        $this->_methodName = __METHOD__;
        $result = $this->GetDataToManageEquipment($idRecord);
        if ($result["errors"])
            return $result;
        
        $data        = $result[0];
        $strArrUsers = "['0', '(Nessuno - Autenticazione obbligatoria)']";
        $strArrLanguages = '';
        $FKUser      = 0;
        while($row = $data->fetch_assoc()){
            if($strArrUsers!="")
                $strArrUsers .=",";
            $strArrUsers .= "['".$row['IDUser']."', '".$row['UserName']."']";
        }
        if ($strArrUsers != "")
            $strArrUsers = "[".$strArrUsers."]";
        
        $data = $result[1];
        $strArrSTBModels = "";
        while($row = $data->fetch_assoc()){
            if($strArrSTBModels!="")
                $strArrSTBModels .=",";
            else
                $STBModel = $row['IDSTBModel'];
            $strArrSTBModels .= "['".$row['IDSTBModel']."', '".$row['Name']."']";
        }
        if ($strArrSTBModels != "")
            $strArrSTBModels = "[".$strArrSTBModels."]";
        
        $data = $result[2];
        while($row = $data->fetch_assoc()){
            if($strArrLanguages != "")
                $strArrLanguages .=",";
            else
                $FKLanguage = $row['IDSTBLanguage'];
            $strArrLanguages .= "['".$row['IDSTBLanguage']."', '".$row['Name']."']";
        }
        $strArrLanguages = "[".$strArrLanguages."]";
        
        $Name       = "";
        $Identifier = "";
        
        $LiveTVPrevState = "1"; // "0";
        $LiveTVPrevScale = "2"; //"0";
        $LiveTVPrevPosX  = "307"; //"0";
        $LiveTVPrevPosY  = "402"; //"0";
        
        $VODPrevState = "1"; // "0";
        $VODPrevScale = "2"; //"0";
        $VODPrevPosX  = "300"; //"0";
        $VODPrevPosY  = "415"; //"0";
        
        $ManagePinValue            = 0;
        $InfoHotelTrue             = 0;
        $InfoHotelFalse            = 1;
        $ParentalControlTrue       = 0;
        $ParentalControlFalse      = 1;
        $PurchasingManagementTrue  = 0;
        $PurchasingManagementFalse = 1;
        
        $Op = 3;
        
        if($idRecord>0){
            $Op = 4;
            $data = $result[3];
            if ($data==null){
                $gErr = new clsError();
                $gErr->setOrigin($this->_className.$this->_methodName);
                $gErr->setNumber(-1000);
                $gErr->setMessage("Group data not found");
                $gErr->setTitle("Error");
                //$gErr->setDetails("Id: ".$idGroup); // da dove verrebbe idGroup?
                $ret = array("success"=>false, "errors"=>true, "values"=>$gErr);
                return $ret;
            }
            $row        = $data->fetch_assoc();
            $Name       = $row['Name'];
            $Identifier = $row['Identifier'];
            $STBModel   = $row['FKSTBModel'];
            $FKLanguage = $row['FKLanguage'];
            if ($row['IPAddress'] != null)
                $IPAddress = $row['IPAddress'];
            else
                $IPAddress = "DHCP";
            $Port    = $row['Port'];
            $STB_MAC = $row['MAC'];
            
            if ($row['FKUser']!="")
                $FKUser = $row['FKUser'];
            
            $LiveTVPrevState = $row['LiveTVPrevState'];
            $LiveTVPrevScale = $row['LiveTVPrevScale'];
            $LiveTVPrevPosX  = $row['LiveTVPrevPosX'];
            $LiveTVPrevPosY  = $row['LiveTVPrevPosY'];
            
            $VODPrevState = $row['VODPrevState'];
            $VODPrevScale = $row['VODPrevScale'];
            $VODPrevPosX  = $row['VODPrevPosX'];
            $VODPrevPosY  = $row['VODPrevPosY'];
            
            $WelcomeMessage = $row['WelcomeMessage'];
            
            $ManagePinValue=$row['ManagePinValue'];
            
            if($row['InfoHotel']!=0){
                $InfoHotelTrue  =1;
                $InfoHotelFalse=0;
            }else{
                $InfoHotelTrue  =0;
                $InfoHotelFalse=1;
            }
            
            if($row['ParentalControl']!=0){
                $ParentalControlTrue  =1;
                $ParentalControlFalse=0;
            }else{
                $ParentalControlTrue  =0;
                $ParentalControlFalse=1;
            }
            
            if($row['PurchasingManagement']!=0){
                $PurchasingManagementTrue =1;
                $PurchasingManagementFalse=0;
            }else{
                $PurchasingManagementTrue =0;
                $PurchasingManagementFalse=1;
            }
        }
        
        $params=ARRAY();
        //$params['Description']=$Description;
        $params['FKLanguage']                   = $FKLanguage;
        $params['FKUser']                       = $FKUser;
        $params['IPAddress']                    = $IPAddress;
        $params['Identifier']                   = $Identifier;
        $params['InfoHotelFalse']               = $InfoHotelFalse;
        $params['InfoHotelTrue']                = $InfoHotelTrue;
        $params['LiveTVPrevPosX']               = $LiveTVPrevPosX;
        $params['LiveTVPrevPosY']               = $LiveTVPrevPosY;
        $params['LiveTVPrevScale']              = $LiveTVPrevScale;
        $params['LiveTVPrevState']              = $LiveTVPrevState;
        $params['ManagePin']                    = $ManagePinValue;
        $params['Name']                         = $Name;
        $params['Op']                           = $Op;
        $params['ParentalControlFalse']         = $ParentalControlFalse;
        $params['ParentalControlTrue']          = $ParentalControlTrue;
        $params['Port']                         = $Port;
        $params['PurchasingManagementFalse']    = $PurchasingManagementFalse;
        $params['PurchasingManagementTrue']     = $PurchasingManagementTrue;
        $params['STBModel']                     = $STBModel;
        $params['STB_MAC']                      = $STB_MAC;
        $params['VODPrevPosX']                  = $VODPrevPosX;
        $params['VODPrevPosY']                  = $VODPrevPosY;
        $params['VODPrevScale']                 = $VODPrevScale;
        $params['VODPrevState']                 = $VODPrevState;
        $params['WelcomeMessage']               = $WelcomeMessage;
        $params['idRecord']                     = $idRecord;
        $params['strArrLanguages']              = $strArrLanguages;
        $params['strArrSTBModels']              = $strArrSTBModels;
        $params['strArrUsers']                  = $strArrUsers;
        return json_encode($params);
    }
    
    public function GetHtmlDetailsRow($odd, $field, $value){
        $this->_methodName = __METHOD__;
        if($odd == 1) $style = "style='background:#E1E1E1' height='20px'"; else $style='';
        
        $html = "<tr $style>"
            . "<td width='30px' style='padding:5px'>$field</td>"
            . "<td style='font-weight: bold;' width='200'>$value</td>"
            . "</tr>"
        ;
        
        return $html;
    }
    
    PUBLIC FUNCTION GARAVOT_Get_Tariffs(){
        $this->_methodName = __METHOD__;
        
        $query_channels_groups = "
            SELECT DISTINCT GR.IDGroupResources AS id, GR.Name AS name
            FROM t_GroupsResources          GR
            JOIN t_ResourcesTypologies      RT  ON RT.IDResourceTypology=GR.IDResourceTypology
            WHERE RT.Name = 'LiveChannel'
        ;";
        $result      = $this->ExecuteQuery($query_channels_groups);
        $errorNumber = $this->GetErrorNumber();
        IF (($errorNumber != "0" && $errorNumber != "") || ($result==NULL || $result<0)) {
            $ret = ARRAY(
                "success" => FALSE,
                "errors"  => TRUE,
                "type"    => "error",
                "values"  => $this->RetriveError()
            );
            RETURN $ret;
        }
        
        $ret = ARRAY(
            "type"   => 'getCode',
            "values" => $result
        );
        RETURN $ret;
    }
    
    public function GetLanguagesForStalker() {
        $this->_methodName = "GetLanguagesForStalker";
        
        $sqlSelectList = "SELECT IDSTBLanguage AS ID, Name FROM t_STBLanguages; ";
        $sqlSelectList.= " SELECT FOUND_ROWS() AS TotRows; ";
        $resultSelectList = $this->GetMultipleDataset($sqlSelectList);
        
        $errorNumber = $this->GetErrorNumber();
        if ($errorNumber!="0" && $errorNumber!="")
        {
            $ret = array("success"=>false, "errors"=>true, "values"=>$this->RetriveError($errorNumber));
            $ret = json_encode($ret);
            die($ret);
        }
        
        $result = $resultSelectList[0];
        $resultRows = $resultSelectList[1];
        
        $row = $resultRows->fetch_assoc();
        $totRows = $row["TotRows"];
        
        while ($row = $result->fetch_assoc()){
            $item = Array(
                "id"					=> $row["ID"],
                "name" 					=> $row["Name"]
            );
            
            $itemList[] = $item;
        }
        
        if($itemList == "") $itemList = "";
        $ret = json_encode(array("rows"=>$totRows, "data"=>$itemList));
        $result->close();
        die($ret);
    }
    
    public function GetWelcomeMessagesForStalker() {
        $this->_methodName = "GetWelcomeMessagesForStalker";
        
        $post		= $_POST;
        
        $idSTBLanguage = $post["idLanguage"];
        
        $sqlSelectList = "SELECT IDWelcomeMessage AS ID, Name FROM t_WelcomeMessages WHERE IDSTBLanguage = $idSTBLanguage; ";
        $sqlSelectList.= " SELECT FOUND_ROWS() AS TotRows; ";
        $resultSelectList = $this->GetMultipleDataset($sqlSelectList);
        
        $errorNumber = $this->GetErrorNumber();
        if ($errorNumber!="0" && $errorNumber!="")
        {
            $ret = array("success"=>false, "errors"=>true, "values"=>$this->RetriveError($errorNumber));
            $ret = json_encode($ret);
            die($ret);
        }
        
        $result = $resultSelectList[0];
        $resultRows = $resultSelectList[1];
        
        $row = $resultRows->fetch_assoc();
        $totRows = $row["TotRows"];
        
        while ($row = $result->fetch_assoc()){
            $item = Array(
                "id"					=> $row["ID"],
                "name" 					=> $row["Name"]
            );
            
            $itemList[] = $item;
        }
        
        if($itemList == "") $itemList = "";
        $ret = json_encode(array("rows"=>$totRows, "data"=>$itemList));
        $result->close();
        die($ret);
    }
    
    public function SetSTBLanguage() {
        $this->_methodName = "SetSTBLanguage";
        
        $post		= $_POST;
        
        $idSTBLanguage = $post["idSTBlanguage"];
        
        $updatedValues = json_decode($post["stb_list"], true);
        
        for ($i = 0; $i < sizeof($updatedValues); $i++) {
            
            $fkResourceToSet = $updatedValues[$i];
            
            $sqlUpd = "UPDATE t_STBEquipments SET FKLanguage = $idSTBLanguage WHERE FKResource = $fkResourceToSet";
            $result = $this->GetDataset($sqlUpd);
            
            $errorNumber = $this->GetErrorNumber();
            if ($errorNumber!="0" && $errorNumber!="")
            {
                $ret = array("success"=>false, "errors"=>true, "values"=>$this->RetriveError($errorNumber));
                $ret = json_encode($ret);
                die($ret);
            }
        }
        
        $ret = array("success"=>true, "errors"=>false, "values"=>"OK");
        $ret = json_encode($ret);
        die($ret);
    }
    
    public function SetSTBWelcomeMsg() {
        $this->_methodName = "SetWelcomeMsg";
        
        $post		= $_POST;
        
        $idWelcomeMsg = $post["idWelcomeMsg"];
        
        $qrySelWelcMsg = "SELECT Message FROM t_WelcomeMessages WHERE IDWelcomeMessage = $idWelcomeMsg";
        $resultSelWelcMsg = $this->GetDataset($qrySelWelcMsg);
        $rowSelWelcMsg = $resultSelWelcMsg->fetch_assoc();
        $welcomeMsg = $rowSelWelcMsg["Message"];
        
        // RECUPERARE TESTO DA DB
        
        if ($post["customerName"] != null)
            $customerName = $post["customerName"];
        else
            $customerName = "";
        
        $calculatedWelcomeMsg = str_replace("##customer##", $customerName, $welcomeMsg);
        
        $updatedValues = json_decode($post["stb_list"], true);
        
        for ($i = 0; $i < sizeof($updatedValues); $i++) {
            
            $fkResourceToSet = $updatedValues[$i];
            
            $sqlUpd = "UPDATE t_STBEquipments SET WelcomeMessage = '$calculatedWelcomeMsg' WHERE FKResource = $fkResourceToSet";
            $result = $this->GetDataset($sqlUpd);
            
            $errorNumber = $this->GetErrorNumber();
            if ($errorNumber!="0" && $errorNumber!="")
            {
                $ret = array("success"=>false, "errors"=>true, "values"=>$this->RetriveError($errorNumber));
                $ret = json_encode($ret);
                die($ret);
            }
        }
        
        $ret = array("success"=>true, "errors"=>false, "values"=>"OK");
        $ret = json_encode($ret);
        die($ret);
    }
}
//class