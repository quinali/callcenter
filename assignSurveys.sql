/*CREAMOS EL PROCEDURE*/

CREATE PROCEDURE `assignSurveys`(surveyId INT,nOperadores INT)
BEGIN
  
  SET @table_name = concat("tokens_",surveyId);
  SET @rank=0;
  
  
  SET @sql_update_0 = concat(
        "UPDATE ",
        @table_name,
        " SET attribute_1 = NULL;");
  
  PREPARE stmt1 FROM @sql_update_0;
  EXECUTE stmt1;
  DEALLOCATE PREPARE stmt1;

  
  
  SET @sql_update_1 = concat(
        "UPDATE (SELECT tok.tid,1+FLOOR((@rank := @rank+1)/((select count(1) from ",
        @table_name,
        " where `completed` ='N')/",
        nOperadores,
        ")) as idOperador FROM ",
        @table_name,
        " tok WHERE `completed` ='N' ORDER BY idOperador ) as tempOperador left join ",
        @table_name,
        " tok on tok.tid = tempOperador.tid SET tok.attribute_1=tempOperador.idOperador WHERE tok.tid = tempOperador.tid;");


#select @sql_update_1 ;

  PREPARE stmt1 FROM @sql_update_1;
  EXECUTE stmt1;
  DEALLOCATE PREPARE stmt1;


#UPDATE `lime_tokens_surveyId` SET attribute_1=nOperadores WHERE attribute_1 = (nOperadores+1);
 SET @sql_update_2 = concat(
    "UPDATE `",
    @table_name,
    "` SET attribute_1=",
    nOperadores,
    " WHERE attribute_1 = (",
    nOperadores,
    "+1);");

PREPARE stmt2 FROM @sql_update_2;
  EXECUTE stmt2;
  DEALLOCATE PREPARE stmt2;

END//