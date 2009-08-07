

<%
	


'Find the number of nights the start day and checkout is on the sameday it is assumed it is one night

    dtNights = (dtEndDay - dtStartDay)
	
	If dtNights = "0" Then
	dtNights = "1"
	Else
	End If
	
	 If dtStartDate = dtEndDate Then 
	 dtEndDate = DateSerial(Year(dtEndDate), Month(dtEndDate), Day(dtEndDate)+1 )
	 Else
	 End If
	 
  


	 
 'Set dates to the days in the reservation	 

dtDay1 = dtStartDate 
dtDay2 = DateSerial(Year(dtStartDate), Month(dtStartDate), Day(dtStartDate)+1 )
dtDay3 = DateSerial(Year(dtStartDate), Month(dtStartDate), Day(dtStartDate)+2 )
dtDay4 = DateSerial(Year(dtStartDate), Month(dtStartDate), Day(dtStartDate)+3 )
dtDay5 = DateSerial(Year(dtStartDate), Month(dtStartDate), Day(dtStartDate)+4 )
dtDay6 = DateSerial(Year(dtStartDate), Month(dtStartDate), Day(dtStartDate)+5 )

If dtCabinStyle <> "0" Then

strSQL2 = "SELECT * "_
& "FROM Units "_
& "WHERE Units.UnitStyle = " & dtCabinStyle & ""

Set Rs2 = AdminConn.Execute(strSQL2)

dtUnitLink = Rs2("UnitLink")
dtUnitPicture = Rs2("UnitPicture")
dtUnitDetails = Rs2("UnitDescription")
dtUnitRates = Rs2("UnitRates")
dtUnitName = Rs2("UnitName")
dtUnitIcon1 = Rs2("UnitIcon1")
dtUnitIcon2 = Rs2("UnitIcon2")
dtUnitIcon3 = Rs2("UnitIcon3")
dtUnitIcon4 = Rs2("UnitIcon4")
dtUnitIcon5 = Rs2("UnitIcon5")
dtUnitIcon6 = Rs2("UnitIcon6")
dtUnitIcon7 = Rs2("UnitIcon7")
dtUnitIcon8 = Rs2("UnitIcon8")
dtUnitIcon9 = Rs2("UnitIcon9")
dtUnitIcon10 = Rs2("UnitIcon10")

    Rs2.close
	Set Rs2 = nothing
	
 
 End If

' Search by style and see if there is availability for each day 
' Npte: This is not used for searching by all available.

If dtCabinStyle <> "0" AND dtNights = "1" Then
strSQL = "Day1Query'" & dtDay1 & "', '" & dtCabinStyle & "'"

ELSEIF dtCabinStyle <> "0" AND dtNights = "2" Then
strSQL = "Day2Query'" & dtDay1 & "', '" & dtDay2 & "', '" & dtCabinStyle & "'"
	   
ELSEIF dtCabinStyle <> "0" AND dtNights = "3" Then
strSQL = "Day3Query'" & dtDay1 & "', '" & dtDay2 & "','" & dtDay3 & "','" & dtCabinStyle & "'"

ELSEIF dtCabinStyle <> "0" AND dtNights = "4" Then	   
strSQL = "Day4Query'" & dtDay1 & "', '" & dtDay2 & "','" & dtDay3 & "','" & dtDay4 & "','" & dtCabinStyle & "'"

ELSEIF dtCabinStyle <> "0" AND dtNights = "5" Then
strSQL = "Day5Query'" & dtDay1 & "', '" & dtDay2 & "','" & dtDay3 & "','" & dtDay4 & "','" & dtDay5 & "','" & dtCabinStyle & "'"

ELSEIF dtCabinStyle <> "0" AND dtNights = "6" Then	   
strSQL = "Day6Query'" & dtDay1 & "', '" & dtDay2 & "','" & dtDay3 & "','" & dtDay4 & "','" & dtDay5 & "', '" & dtDay6 & "', '" & dtCabinStyle & "'"
ELSE
END IF

IF dtCabinStyle <> "0" Then
Set Rs = ConnQuery.Execute(strSQL)
End If
 ' here we need to rewrite this to be useful even if = 0
 

If dtCabinStyle <> "0"  Then

If Rs.BOF = "True" Then
		    dtBooked = ""
		  
           Else
	       dtBooked = Rs("Uid")
           End IF
		

If dtBooked <> "" Then
dtDateAvail = True
dtAvailImg = "icon_avail.gif"
dtAvailTxt = "Available"
Else
dtDateAvail = False
dtAvailImg = "icon_noavail.gif"
dtAvailTxt = "Not Available"
End If

End If
IF dtCabinStyle <> "0" Then
Rs.close
Set Rs = nothing
End If
%>

<body>

<%
If dtCabinStyle <> "0" Then

'Find correct rate

strSQL3 = "SELECT TOP 1 UnitRateName.*, UnitRateSeason.SD, UnitRateSeason.Rate "_
& "FROM UnitRateSeason LEFT JOIN UnitRateName ON UnitRateSeason.URNId = UnitRateName.URNId "_
& "WHERE (((UnitRateSeason.SD)<=#"& dtStartDate &"#) AND ((UnitRateName.URId)="& dtCabinStyle &") AND ((UnitRateName.Sort)="& dtAdults &")) "_
& "ORDER BY UnitRateSeason.SD DESC"
Set Rs3 = Conn.Execute(strSQL3)

dtNightlyRate = (Rs3("Rate"))
rateDescription = Rs3("Description")
dtRateTotal = ccur(dtNights * dtNightlyRate)
dtStateTax = ccur(dtRateTotal * .08)
dtLocalTax = ccur(dtRateTotal * .03)
dtTotal = ccur(dtRateTotal) + ccur(dtStateTax) + ccur(dtLocalTax)
dtDepositDue = ccur(dtRateTotal * .5)

Rs3.close
Set Rs3 = nothing

strWriteTable = strWriteTable & " <table border=0 cellpadding=0 cellspacing=0 width=557> "_
 							  & " <tbody> <tr> <td  colspan=4 valign=middle><table border=0> <tbody> "_
          					  & " <tr> <td height=9 colspan=2 align=left valign=top> <img src=/templates/img/header_dottedline_557.gif width=557 height=5></td> "_
                              & " </tr><tr><td width=31 height=9 align=left valign=top><img src=/img/logo_wba.jpg width=31 height=31></td><td width=522 bgcolor=#ECF3DA > "_
							  & "<a href=" & dtUnitLink & ">" & dtUnitName & "</a></td></tr></tbody></table> "_
							  & "</td></tr><tr><td height=12 width=75><img src=img/spacer_003.gif height=1 width=75></td><td width=315><img src=img/spacer_003.gif height=1 width=311></td>"_
							  & "<td><img src=img/spacer_003.gif height=1 width=1></td><td width=166><img src=img/spacer_003.gif height=1 width=166></td>"_
							  & "</tr><tr><td colspan=2 rowspan=2 valign=top width=390><table border=0 cellpadding=2 cellspacing=0>"_
         					  & " <tbody><tr><td valign=top width=75><img src=/img/" & dtUnitPicture & " width=179 height=119 border=1> </td><td class=hotelinfo valign=top width=311>" & dtUnitDetails & " <br> "_
							  & " <br><img src=img/spacer_003.gif height=11 width=1><br></td></tr><tr><td colspan=2></td></tr></tbody></table><span> <img src=/img/" & dtUnitIcon1 & " > <img src=/img/" & dtUnitIcon2 & " ><img src=/img/" & dtUnitIcon3 & " ><img src=/img/" & dtUnitIcon4 & " ><img src=/img/" & dtUnitIcon5 & " ><img src=/img/" & dtUnitIcon6 & " ><img src=/img/" & dtUnitIcon7 & " ><img src=/img/" & dtUnitIcon8 & " ><img src=/img/" & dtUnitIcon9 & " ><img src=/img/" & dtUnitIcon10 & " ><br> "_
							  & "</span></td><td rowspan=2 width=1><img src=img/spacer_003.gif height=1 width=1></td> <td valign=top width=143> "_
							  & "<table> <tbody><tr><td width=27 valign=top class=availability><img src=img/" & dtAvailImg & " height=23 width=23> </td> <td width=113 valign=top> <span> "_
							  & " "& dtAvailTxt& "</span></td></tr><tr valign=top><td height=81 colspan=2><img src=img/spacer_003.gif height=22 width=1><br> "_
							  & "<span>  <span>$" & CCur(dtNightlyRate) & ".00</span><br> USD / Night + tx  <br> </span> </td></tr></tbody></table></td> </tr><tr> "_
							  & "<td align=left valign=bottom> "_
							  & "<form name=form1 method=post action=step3.asp? > "_
								& "<input type=hidden name=startDate value=" & dtStartDate & "> "_
								& "<input type=hidden name=endDate value=" & dtEndDate  & "> "_
								& "<input type=hidden name=startDay value=" & dtStartDay  & "> "_
								& "<input type=hidden name=endDay value=" & dtEndDay  & "> "_
								& "<input type=hidden name=urid value=" & dtUnitStyle  & "> "_
								& "<input type=hidden name=adults value=" & dtAdults  & "> "_
								& "<input type=hidden name=children value=" & dtChildren  & "> "_
								& "<input type=hidden name=sortOrder value=" & dtSortOrder & "> "_
								& "<input type=hidden name=nights value=" & dtNights  & "> "_
								& "<input type=hidden name=nightlyRate value=" & dtNightlyRate  & "> "_
								& "<input type=hidden name=nightlyRateTotal value=" & dtRateTotal  & "> "_
								& "<input type=hidden name=stateTax value=" & dtStateTax  & "> "_
								& "<input type=hidden name=localTax value=" & dtLocalTax  & "> "_
								& "<input type=hidden name=reservationTotal value=" & dtTotal  & "> "_
								& "<input type=hidden name=depositDue value=" & dtDepositDue  & "> "_
								& "<input type=hidden name=cabinName value=" & cabinName  & "> "_
								& "<input type=hidden name=cabinStyle value=" & cabinStyle  & "> "_
							    & " <INPUT TYPE=image SRC=img/button_viewrates_neutral.gif WIDTH=86  HEIGHT=18 ALT=SUBMIT! > </a></form> </td></tr></tbody></table> "_
								& "<br>"
								
 
							  
							  response.write strWriteTable

ELSEIF dtCabinStyle = "0" Then

strSQL4 = "SELECT * "_
         & "From Units "
		 Set Rs4 = AdminConn.Execute(strSQL4)
		 
			  If NOT Rs4.EOF Then 
			  
			  strSelectCabinStyle = strSelectCabinStyle & ""
			  
			   End If
			  
										  While NOT Rs4.EOF
			  
										  		    dtUnitName = Rs4("UnitName")
													dtUnitStyle = Rs4("UnitStyle")
													dtUnitLink = Rs4("UnitLink")
													dtUnitDetails = Rs4("UnitDescription")
													dtUnitPicture = Rs4("UnitPicture")
													dtUnitRates = Rs4("UnitRates")
													dtUnitIcon1 = Rs4("UnitIcon1")
													dtUnitIcon2 = Rs4("UnitIcon2")
													dtUnitIcon3 = Rs4("UnitIcon3")
													dtUnitIcon4 = Rs4("UnitIcon4")
													dtUnitIcon5 = Rs4("UnitIcon5")
													dtUnitIcon6 = Rs4("UnitIcon6")
													dtUnitIcon7 = Rs4("UnitIcon7")
													dtUnitIcon8 = Rs4("UnitIcon8")
													dtUnitIcon9 = Rs4("UnitIcon9")
													dtUnitIcon10 = Rs4("UnitIcon10")
													 
													dtOccSort = dtAdults
													'dtUnitAvail = ?
													'Unit1AvailTxt
													
													' This is a validation catch for checking max occupancies of units

														' Check the max capacity of the cabins against the total num ber of people they are searching for	
													
													If dtUnitStyle = "5" Then ' Grandview
													dtMaxCap = "5"
													ElseIF dtUnitStyle = "7" Then ' 2 Bedroom
													dtMaxCap = "5"
													ElseIF dtUnitStyle = "6" Then ' 1 Bedroom
													dtMaxCap = "4"
													ElseIF dtUnitStyle = "8" Then ' CEO
													dtMaxCap = "7"
													ELSE
													End If
													
													dtTotalOcc = ccur(dtAdults) + ccur(dtChildren)
													
													If ccur(dtTotalOcc) > ccur(dtMaxCap) Then
													dtProblem = True
													ElseIf ccur(dtTotalOcc) = ccur(dtMaxCap) Then 
													dtProblem = True
													Else
													dtProblem = False
													End If
													
													
													
									If dtNights = "1" Then
									strSQL5 = "Day1Query'" & dtDay1 & "', '" & dtUnitStyle & "'"
									
									ELSEIF  dtNights = "2" Then
									strSQL5 = "Day2Query'" & dtDay1 & "', '" & dtDay2 & "', '" & dtUnitStyle & "'"
										   
									ELSEIF  dtNights = "3" Then
									strSQL5 = "Day3Query'" & dtDay1 & "', '" & dtDay2 & "','" & dtDay3 & "','" & dtUnitStyle & "'"
									
									ELSEIF  dtNights = "4" Then	   
									strSQL5 = "Day4Query'" & dtDay1 & "', '" & dtDay2 & "','" & dtDay3 & "','" & dtDay4 & "','" & dtUnitStyle & "'"
									
									ELSEIF  dtNights = "5" Then
									strSQL5 = "Day5Query'" & dtDay1 & "', '" & dtDay2 & "','" & dtDay3 & "','" & dtDay4 & "','" & dtDay5 & "','" & dtUnitStyle & "'"
									
									ELSEIF  dtNights = "6" Then	   
									strSQL5 = "Day6Query'" & dtDay1 & "', '" & dtDay2 & "','" & dtDay3 & "','" & dtDay4 & "','" & dtDay5 & "', '" & dtDay6 & "', '" & dtUnitStyle & "'"
									
									END IF
									Set Rs5 = ConnQuery.Execute(strSQL5)			
									
If Rs5.BOF = "True" Then
		    dtBooked = ""
		  
           Else
	       dtBooked = Rs5("Uid")
           End IF		
									
									
									

									If dtBooked <> "" and dtProblem = False Then
									dtDateAvail = True
									dtAvailImg = "icon_avail.gif"
									dtAvailTxt = "Available"
									dtBookedImg = "<INPUT TYPE=image SRC=img/button_viewrates_neutral.gif WIDTH=86  HEIGHT=18 ALT=SUBMIT!"
									ElseIf dtBooked <> "" and dtProblem = True Then
									dtDateAvail = False
									dtAvailImg = "icon_noavail.gif"
									dtBookedImg = "<img name=booked src=img/button_neutral.gif width=86 height=18 border=0 alt= "
									dtAvailTxt = "Not Available Max " & CCur((dtMaxCap) -1) & "p"
									Else
									dtDateAvail = False
									dtAvailImg = "icon_noavail.gif"
									dtBookedImg = "<img name=booked src=img/button_neutral.gif width=86 height=18 border=0 alt= "
									dtAvailTxt = "Not Available"
									End If
									
									If dtUnitStyle = "8" Then
									dtOccSort = "1"
									End If
									
									If dtProblem = True Then
									dtNightlyRate= "0"
									Else
									
								'	response.write dtOccSort
									
									strSQL3 = "SELECT TOP 1 UnitRateName.*, UnitRateSeason.SD, UnitRateSeason.Rate "_
					& "FROM UnitRateSeason LEFT JOIN UnitRateName ON UnitRateSeason.URNId = UnitRateName.URNId "_
					& "WHERE (((UnitRateSeason.SD)<=#"& dtStartDate &"#) AND ((UnitRateName.URId)="& dtUnitStyle &") AND ((UnitRateName.Sort)="& dtOccSort &")) "_
					& "ORDER BY UnitRateSeason.SD DESC"
					Set Rs3 = Conn.Execute(strSQL3)
					
					If Rs3.BOF = "True" Then
		       dtNightlyRate = "0.00"
		   rateDescription = "N/A"
		  
           Else
					dtNightlyRate = (Rs3("Rate"))
					rateDescription = Rs3("Description")
           End IF	
					

					dtRateTotal = ccur(dtNights * dtNightlyRate)
					dtStateTax = ccur(dtRateTotal * .08)
					dtLocalTax = ccur(dtRateTotal * .03)
					dtTotal = ccur(dtRateTotal) + ccur(dtStateTax) + ccur(dtLocalTax)
					dtDepositDue = ccur(dtRateTotal * .5)
					Rs3.close
					Set Rs3 = nothing
					End If
					
					


strWriteTable = strWriteTable & " <table border=0 cellpadding=0 cellspacing=0 width=557> "_
 							  & " <tbody> <tr> <td  colspan=4 valign=middle><table border=0> <tbody> "_
          					  & " <tr> <td height=9 colspan=2 align=left valign=top> <img src=/templates/img/header_dottedline_557.gif width=557 height=5></td> "_
                              & " </tr><tr><td width=31 height=9 align=left valign=top><img src=/img/logo_wba.jpg width=31 height=31></td><td width=522 bgcolor=#ECF3DA > "_
							  & "<a href=" & dtUnitLink & ">" & dtUnitName &"</a></td></tr></tbody></table> "_
							  & "</td></tr><tr><td height=12 width=75><img src=img/spacer_003.gif height=1 width=75></td><td width=315><img src=img/spacer_003.gif height=1 width=311></td>"_
							  & "<td><img src=img/spacer_003.gif height=1 width=1></td><td width=166><img src=img/spacer_003.gif height=1 width=166></td>"_
							  & "</tr><tr><td colspan=2 rowspan=2 valign=top width=390><table border=0 cellpadding=2 cellspacing=0>"_
         					  & " <tbody><tr><td valign=top width=75><img src=/img/" & dtUnitPicture & " width=179 height=119 border=1> </td><td class=hotelinfo valign=top width=311>" & dtUnitDetails & "<br> "_
							  & " <br><img src=img/spacer_003.gif height=11 width=1><br></td></tr><tr><td colspan=2></td></tr></tbody></table><span> <img src=/img/" & dtUnitIcon1 & " > <img src=/img/" & dtUnitIcon2 & " ><img src=/img/" & dtUnitIcon3 & " ><img src=/img/" & dtUnitIcon4 & " ><img src=/img/" & dtUnitIcon5 & " ><img src=/img/" & dtUnitIcon6 & " ><img src=/img/" & dtUnitIcon7 & " ><img src=/img/" & dtUnitIcon8 & " ><img src=/img/" & dtUnitIcon9 & " ><img src=/img/" & dtUnitIcon10 & " ><br> "_
							  & "</span></td><td rowspan=2 width=1><img src=img/spacer_003.gif height=1 width=1></td> <td valign=top width=143> "_
							  & "<table> <tbody><tr><td width=27 valign=top class=availability><img src=img/" & dtAvailImg & " height=23 width=23> </td> <td width=113 valign=top> <span> "_
							  & "" & dtAvailTxt & " </span></td></tr><tr valign=top><td height=81 colspan=2><img src=img/spacer_003.gif height=22 width=1><br> "_
							  & "<span> <span>$" & CCur(dtNightlyRate) & ".00</span><br> USD / Night + tx <br> </span> </td></tr></tbody></table></td> </tr><tr> "_
							  & "<td align=left valign=bottom> "_
							  & "<form name=form1 method=post action=step3.asp? > "_
								& "<input type=hidden name=startDate value=" & dtStartDate & "> "_
								& "<input type=hidden name=endDate value=" & dtEndDate  & "> "_
								& "<input type=hidden name=startDay value=" & dtStartDay  & "> "_
								& "<input type=hidden name=endDay value=" & dtEndDay  & "> "_
								& "<input type=hidden name=urid value=" & dtUnitStyle  & "> "_
								& "<input type=hidden name=adults value=" & dtAdults  & "> "_
								& "<input type=hidden name=children value=" & dtChildren  & "> "_
								& "<input type=hidden name=sortOrder value=" & dtSortOrder & "> "_
							    & dtBookedImg & "  > </a></form> </td></tr></tbody></table> "_
								& "<br>"

 Rs4.MoveNext
										 
										 WEND
										

                    Response.write strWriteTable
					Rs4.close
													Set Rs4 = nothing
					End IF
					
					
	
	AdminConn.Close
	set AdminConn = nothing
	
	Conn.Close
    set Conn = nothing
	
	ConnQuery.Close
    set ConnQuery = nothing

%>
