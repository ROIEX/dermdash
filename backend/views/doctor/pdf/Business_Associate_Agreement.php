<?php
use common\models\Doctor;
use common\components\dateformatter\FormatDate;

?>

<HTML>
<BODY>
<DIV id="page_1">

<P class="p0 ft0">BUSINESS ASSOCIATE AGREEMENT</P>
<P class="p1 ft0"><SPAN class="ft1">This </SPAN>BUSINESS ASSOCIATE AGREEMENT <SPAN class="ft2">(this “</SPAN>BA Agreement<SPAN class="ft2">”) is made by and </SPAN><SPAN class="ft1">between MOLE CHECK APP INC</SPAN><SPAN class="ft2">., a Delaware corporation (“</SPAN>Company<SPAN class="ft2">”),
</SPAN><SPAN class="ft1">and <?php echo $doctor->firstname. " " . $doctor->lastname . " " . Doctor::getDoctorType($doctor->doctor_type) ?></SPAN></P>
<P class="p2 ft2">(“<SPAN class="ft0">Covered Entity</SPAN>”) and is effective <SPAN class="ft1">as of the </SPAN>date last signed below (“<SPAN class="ft0">Effective Date</SPAN>”). Capitalized terms used in this BA Agreement</P>
<P class="p3 ft3">without definition shall have the respective meanings assigned to such terms in the Administrative Simplification section of the Health Insurance Portability and Accountability</P>
<P class="p4 ft1">Act of 1996, the Health Information Technology for Economic and Clinical Health Act and their <SPAN class="ft2">implementing regulations as amended from time to time (collectively, “</SPAN><SPAN class="ft0">HIPAA</SPAN><SPAN class="ft2">”).</SPAN></P>
<P class="p5 ft0">RECITALS</P>
<P class="p6 ft3"><SPAN class="ft4">WHEREAS</SPAN>, Covered Entity and Company are parties to certain agreement(s) setting</P>
<P class="p7 ft5">forth certain services that require Company to have access to Protected Health Information</P>
<P class="p7 ft2">(collectively, the “License Agreement”);</P>
<P class="p8 ft3"><SPAN class="ft4">NOW THEREFORE, </SPAN>in consideration of the mutual premises and covenants contained herein and other good and valuable consideration, the receipt and sufficiency of which are hereby acknowledged, Covered Entity and Company agree as follows:</P>
<P class="p9 ft0">AGREEMENT</P>
<P class="p10 ft0"><SPAN class="ft1">I. </SPAN>GENERAL PROVISIONS.</P>
<P class="p6 ft1"><SPAN class="ft0">Section 1.1 </SPAN><SPAN class="ft6">Effect</SPAN><SPAN class="ft0">. </SPAN>The provisions of this BA Agreement shall control with respect to</P>
<P class="p7 ft7">Protected Health Information that Company receives from or on behalf of Covered Entity</P>
<P class="p7 ft2">(“<SPAN class="ft0">PHI</SPAN>”).</P>
<P class="p11 ft3"><SPAN class="ft4">Section 1.2 </SPAN><SPAN class="ft8">No Third Party Beneficiaries. </SPAN>The parties have not created and do not</P>
<P class="p12 ft1">intend to create by this BA Agreement any third party rights, including, but not limited to, third <SPAN class="ft2">party rights for Covered Entity’s patients.</SPAN></P>
<P class="p6 ft1"><SPAN class="ft0">Section 1.3 </SPAN><SPAN class="ft6">HIPAA Amendments. </SPAN>The parties acknowledge and agree that the</P>
<P class="p7 ft3">Health Information Technology for Economic and Clinical Health Act and its implementing</P>
<P class="p13 ft3">regulations impose requirements with respect to privacy, security and breach notification <SPAN class="ft9">applicable to Business Associates (collectively, the “</SPAN><SPAN class="ft4">HITECH BA Provisions</SPAN><SPAN class="ft9">”). The HITECH</SPAN></P>
<P class="p14 ft3">BA Provisions and any other future amendments to HIPAA affecting Business Associate Agreements are hereby incorporated by reference into this BA Agreement as if set forth in this BA Agreement in their entirety, effective on the later of the effective date of this BA Agreement or such subsequent date as may be specified by HIPAA.</P>
<P class="p15 ft1">Agreements are hereby incorporated by reference into this BA Agreement as if set forth in this</P>
</DIV>
<DIV id="page_2">


<P class="p16 ft1">BA Agreement in their entirety, effective on the later of the effective date of this BA Agreement or such subsequent date as may be specified by HIPAA.</P>
<P class="p17 ft3"><SPAN class="ft4">Section 1.4 </SPAN><SPAN class="ft8">Regulatory References</SPAN>. A reference in this BA Agreement to a section in HIPAA means the section as it may be amended from <NOBR>time-to-time.</NOBR></P>
<P class="p18 ft11"><SPAN class="ft0">II.</SPAN><SPAN class="ft10">COMPANY’S OBLIGATIONS.</SPAN></P>
<P class="p19 ft3"><SPAN class="ft4">Section 2.1 </SPAN><SPAN class="ft8">Use and Disclosure of PHI</SPAN><SPAN class="ft4">. </SPAN>Company may use and disclose PHI as permitted or required under the License Agreement, this BA Agreement and as Required by Law, but shall not otherwise use or disclose any PHI. Company shall not use or disclose PHI received from Covered Entity in any manner that would constitute a violation of HIPAA if so</P>
<P class="p7 ft5">used or disclosed by Covered Entity (except as set forth in Sections 2.1(a), (b) and (c) of this</P>
<P class="p20 ft2">BA Agreement). To the extent Company carries out any of Covered Entity’s o<SPAN class="ft1">bligations under the HIPAA Privacy Rule, Company shall comply with the requirements of the HIPAA Privacy Rule that apply to Covered Entity in the performance of such obligations. Without limiting the generality of the foregoing, Company is permitted to use or disclose PHI as set forth below:</SPAN></P>
<P class="p21 ft1"><SPAN class="ft1">(a)</SPAN><SPAN class="ft12">Company may use PHI internally for its proper management and administrative services or to carry out its legal responsibilities;</SPAN></P>
<P class="p22 ft2"><SPAN class="ft2">(b)</SPAN><SPAN class="ft13">Company may disclose PHI to a third party for Company’s proper </SPAN><SPAN class="ft1">management and administration, provided that the disclosure is Required by Law or Company obtains reasonable assurances from the third party to whom the PHI is to be disclosed that the third party will (1) protect the confidentially of the PHI, (2) only use or further disclose the PHI as Required by Law or for the purpose for which the PHI was disclosed to the third party and (3) notify Company of any instances of which the person is aware in which the confidentiality of the PHI has been breached;</SPAN></P>
<P class="p23 ft1"><SPAN class="ft1">(c)</SPAN><SPAN class="ft12">Company may use PHI to provide Data Aggregation services as defined by HIPAA; and</SPAN></P>
<P class="p24 ft3"><SPAN class="ft3">(d)</SPAN><SPAN class="ft14">Company may use PHI to create </SPAN><NOBR>de-identified</NOBR> health information in accordance with the HIPAA <NOBR>de-identification</NOBR> requirements. Without limiting Company's</P>
<P class="p25 ft1">rights under the License Agreement, Company may disclose, use and/or exploit <NOBR>de-identified</NOBR> health information for any purposes not prohibited by law.</P>
<P class="p26 ft3"><SPAN class="ft4">Section 2.2 </SPAN><SPAN class="ft8">Safeguards</SPAN><SPAN class="ft4">. </SPAN>Company shall use reasonable and appropriate safeguards to prevent the use or disclosure of PHI, except as otherwise permitted or required by this BA Agreement. In addition, Company shall implement Administrative Safeguards, Physical Safeguards and Technical Safeguards that reasonably and appropriately protect the</P>
<P class="p7 ft5">Confidentiality, Integrity and Availability of PHI transmitted or maintained in Electronic Media</P>
<P class="p27 ft2">(“<SPAN class="ft0">EPHI</SPAN>”) that it creates, receives, maintains or transmits on behalf of Covered Entity.</P>
<P class="p28 ft1">Company shall comply with the HIPAA Security Rule with respect to EPHI.</P>
</DIV>
<DIV id="page_3">


<P class="p29 ft17"><SPAN class="ft15">Section 2.3 </SPAN><SPAN class="ft16">Minimum Necessary Standard. </SPAN>To the extent required by the</P>
<P class="p30 ft2">“minimum necessary” requirements of HIPAA, Company shall only request, use and disclose <SPAN class="ft1">the minimum amount of PHI necessary to accomplish the purpose of the request, use or disclosure.</SPAN></P>
<P class="p31 ft3"><SPAN class="ft4">Section 2.4 </SPAN><SPAN class="ft8">Mitigation</SPAN><SPAN class="ft4">. </SPAN>Company shall take reasonable steps to mitigate, to the extent practicable, any harmful effect (that is known to Company) of a use or disclosure of PHI by Company in violation of this BA Agreement.</P>
<P class="p32 ft3"><SPAN class="ft4">Section 2.5 </SPAN><SPAN class="ft8">Subcontractors</SPAN><SPAN class="ft4">. </SPAN>Company shall enter into a written agreement meeting the requirements of 45 C.F.R. §§ 164.504(e) and 164.314(a)(2) with each Subcontractor (including, without limitation, a Subcontractor that is an agent under applicable law) that creates, receives, maintains or transmits PHI on behalf of Company. Company shall ensure that the written agreement with each Subcontractor obligates the Subcontractor to comply with restrictions and conditions that are at least as restrictive as the restrictions and conditions that apply to Company under this BA Agreement.</P>
<P class="p33 ft0">Section 2.6 <SPAN class="ft6">Reporting Requirements.</SPAN></P>
<P class="p34 ft1"><SPAN class="ft1">(a)</SPAN><SPAN class="ft18">If Company becomes aware of a use or disclosure of PHI in violation of this BA Agreement by Company or by a third party to which Company disclosed PHI, Company shall report any such use or disclosure to Covered Entity without unreasonable delay.</SPAN></P>
<P class="p35 ft1"><SPAN class="ft1">(b)</SPAN><SPAN class="ft19">Company shall report any Security Incident involving EPHI of which it becomes aware in the following manner: (a) any actual, successful Security Incident will be reported to Covered Entity in writing without unreasonable delay, and (b) any attempted, unsuccessful Security Incident of which Company becomes aware will be reported to Covered Entity orally or in writing on a reasonable basis, as requested by Covered Entity. If the HIPAA security regulations are amended to remove the requirement to report unsuccessful attempts at unauthorized access, the requirement hereunder to report such unsuccessful attempts will no longer apply as of the effective date of the amendment.</SPAN></P>
<P class="p36 ft1"><SPAN class="ft1">(c)</SPAN><SPAN class="ft18">Company shall, following the discovery of a Breach of Unsecured PHI, notify the Covered Entity of such Breach in accordance with 45 C.F.R. § 164.410 without unreasonable delay and in no case later sixty (60) days after discovery of the Breach.</SPAN></P>
<P class="p37 ft1"><SPAN class="ft0">Section 2.7 </SPAN><SPAN class="ft6">Access to Information. </SPAN>Company shall make available PHI to Covered Entity for so long as Company maintains the PHI in a Designated Record Set. If Company receives a request for access to PHI directly from an Individual, Company shall forward such request to Covered Entity within ten (10) business days. Covered Entity shall have the sole responsibility for determining whether to approve a request for access to PHI and to provide such access to the Individual.</P>
</DIV>
<DIV id="page_4">


<P class="p38 ft3"><SPAN class="ft4">Section 2.8 </SPAN><SPAN class="ft8">Availability of PHI for Amendment. </SPAN>Company shall provide PHI to Covered Entity for amendment, and incorporate any such amendments in the PHI (for so long as Company maintains such information in the Designated Record Set), in accordance with the Agreement and as required by 45 C.F.R. § 164.526. If Company receives a request for amendment to PHI directly from an Individual, Company shall forward such request to Covered Entity within ten (10) business days. Covered Entity shall have the sole responsibility for determining whether to approve an amendment to PHI and to make such amendment.</P>
<P class="p39 ft3"><SPAN class="ft4">Section 2.9 </SPAN><SPAN class="ft8">Accounting of Disclosures. </SPAN>Within thirty (30) business days of written notice by Covered Entity to Company that it has received a request for an accounting of</P>
<P class="p40 ft21">disclosures of PHI (other than disclosures to which an exception to the accounting requirement <SPAN class="ft20">applies), Company shall make available to Covered Entity such information as is in Company’s</SPAN></P>
<P class="p41 ft1">possession and is required for Covered Entity to make the accounting required by 45 C.F.R. § 164.528. Covered Entity shall have the sole responsibility for providing an accounting to the Individual.</P>
<P class="p42 ft3"><SPAN class="ft4">Section 2.10 </SPAN><SPAN class="ft8">Availability of Books and Records</SPAN><SPAN class="ft4">. </SPAN>Following reasonable advance written notice, Company shall make its internal practices, books and records relating to the use</P>
<P class="p7 ft17">and disclosure of PHI received from, or created or received by Company on behalf of, Covered</P>
<P class="p43 ft2">Entity available to the Secretary for purposes of determining Covered Entity’s compliance with</P>
<P class="p28 ft1">HIPAA.</P>
<P class="p44 ft6"><SPAN class="ft0">III.</SPAN><SPAN class="ft22">TERMINATION OF THE AGREEMENT.</SPAN></P>
<P class="p45 ft1"><SPAN class="ft0">Section 3.1 Term. </SPAN>The term of this BA Agreement shall commence on the Effective Date and shall continue for so long as Company maintains any PHI.</P>
<P class="p46 ft0">Section 3.2 <SPAN class="ft6">Termination Upon Breach of Provisions Applicable to PHI. </SPAN><SPAN class="ft1">Any</SPAN></P>
<P class="p47 ft3">other provision of the License Agreement notwithstanding, the License Agreement may be <SPAN class="ft9">terminated by either party (the </SPAN><NOBR><SPAN class="ft9">“</SPAN><SPAN class="ft4">Non-Breaching</SPAN></NOBR><SPAN class="ft4"> Party</SPAN><SPAN class="ft9">”) upon thirty (30) days written notice to the other party (the “</SPAN><SPAN class="ft4">Breaching Party</SPAN><SPAN class="ft9">”) in the event that the Breaching Party materially</SPAN></P>
<P class="p48 ft1">breaches this BA Agreement in any material respect and such breach is not cured within such thirty (30) day period.</P>
<P class="p32 ft0">Section 3.3 <SPAN class="ft6">Return or Destruction of PHI upon Termination</SPAN><SPAN class="ft23">. </SPAN><SPAN class="ft1">Upon termination of this BA Agreement, Company shall return or destroy all PHI received from Covered Entity or created or received by Company on behalf of Covered Entity and which Company still maintains as PHI. Notwithstanding the foregoing, to the extent that Company determines that it is not feasible to return or destroy such PHI, this BA Agreement (including, without limitation, Section 2.1(d)) shall survive termination of the License Agreement and such PHI shall be used or disclosed solely for such purpose or purposes which prevented the return or destruction of such PHI.</SPAN></P>
<P class="p49 ft4">IV. <SPAN class="ft8">OBLIGATIONS OF COVERED ENTITY.</SPAN></P>
<P class="p50 ft1"><SPAN class="ft0">Section 4.1 Permissible Requests. </SPAN>Covered Entity shall not request Company to use or disclose PHI in any manner that would not be permissible under HIPAA if done by Covered</P>
</DIV>
<DIV id="page_5">


<P class="p7 ft1">Entity.</P>
<P class="p51 ft3"><SPAN class="ft4">Section 4.2 . </SPAN><SPAN class="ft8">Minimum Necessary PHI. </SPAN>When Covered Entity discloses PHI to</P>
<P class="p52 ft1">Company, Covered Entity shall provide the minimum amount of PHI necessary for the <SPAN class="ft2">accomplishment of Covered Entity’s purpose.</SPAN></P>
<P class="p46 ft1"><SPAN class="ft0">Section 4.3 </SPAN><SPAN class="ft6">Appropriate Use of PHI. </SPAN>Covered Entity and its employees,</P>
<P class="p7 ft7">representatives, consultants, contractors and agents shall not submit any Protected Health</P>
<P class="p7 ft9">Information to Company (A) outside of Company’s proprietary software marketed</P>
<P class="p14 ft2"><SPAN class="ft1">under the name Mole Check App </SPAN>(“<SPAN class="ft0">Mole Check App Platform</SPAN>”), including but not limited to <SPAN class="ft1">submissions to any online forum made available by Company to its customers, email transmissions, and submissions through any support website, portal, or online help desk or similar service made available by Company outside of Mole Check App Platform; or (B) directly to any third party involved in the provision of an online forum, email, support website, online help desk or other service described in (A), above.</SPAN></P>
<P class="p53 ft24">Section 4.4 Notice of Privacy Practices. <SPAN class="ft5">Except as Required By Law, with</SPAN></P>
<P class="p54 ft1"><SPAN class="ft2">Company’s consent or as set forth in </SPAN>this BA Agreement, Covered Entity shall not include any <SPAN class="ft2">limitation in the Covered Entity’s notice of privacy practices that limits Company’s use or</SPAN></P>
<P class="p27 ft1">disclosure of PHI under the License Agreement.</P>
<P class="p55 ft3"><SPAN class="ft4">Section 4.5 Permissions; Restrictions. </SPAN>Covered Entity warrants that it has obtained and will obtain any consent, authorization and/or other legal permission required under HIPAA and other applicable law for the disclosure of PHI to Company. Covered Entity shall notify</P>
<P class="p56 ft3">Company of any changes in, or revocation of, the permission by an Individual to use or disclose <SPAN class="ft9">his or her PHI, to the extent that such changes may affect Company’s use or disclosure of PHI.</SPAN></P>
<P class="p57 ft21">Covered Entity shall not agree to any restriction on the use or disclosure of PHI under 45 CFR § 164.52<SPAN class="ft20">2 that restricts Company’s use or disclosure of PHI under this Agreement unless such</SPAN></P>
<P class="p58 ft1">restriction is Required By Law or Company grants its written consent, which consent shall not be unreasonably withheld.</P>
<P class="p59 ft0">V. MISCELLANEOUS.</P>
<P class="p60 ft3"><SPAN class="ft4">Section 5.1 Amendments; Waiver. </SPAN>This BA Agreement may not be modified, nor shall any provision hereof be waived or amended, except in a writing duly signed by authorized representatives of the parties. A waiver with respect to one event shall not be construed as continuing, or as a bar to or waiver of any right or remedy as to subsequent events.</P>
<P class="p61 ft1"><SPAN class="ft0">Section 5.2 Notices. </SPAN>Any notices to be given hereunder to a party shall be made via</P>
<P class="p62 ft3">hand delivery, U.S.P.S. Certified Mail Return Receipt Requested, or nationally recognized express <SPAN class="ft9">courier with proof of delivery, to such party’s address as set in the Agreement</SPAN></P>
<P class="p43 ft1">and shall be effective upon actual delivery.</P>
</DIV>
<DIV id="page_6">


<P class="p63 ft3"><SPAN class="ft4">Section 5.3 Enforcement Costs. </SPAN>If any legal action or other proceeding is brought for the enforcement or interpretation of this BA Agreement, or because of an alleged dispute,</P>
<P class="p64 ft3">breach, default or misrepresentation in connection with any provision of this BA Agreement, <SPAN class="ft9">the substantially prevailing party shall be entitled to recover reasonable attorneys’ fees, court</SPAN></P>
<P class="p65 ft1">costs and all expenses incurred in that action or proceeding and at all levels of trial and appeal, in addition to any other relief to which such party may be entitled.</P>
<P class="p66 ft2"><SPAN class="ft0">Section 5.4 </SPAN><SPAN class="ft6">Limitation of Liability</SPAN><SPAN class="ft0">. </SPAN>IN NO EVENT SHALL COMPANY’S</P>
<P class="p67 ft3">AGGREGATE LIABILITY ARISING OUT OF OR RELATED TO THIS BA AGREEMENT, WHETHER IN CONTRACT, TORT OR UNDER ANY OTHER THEORY OF LIABILITY, EXCEED THE AMOUNTS ACTUALLY PAID BY AND DUE FROM COVERED ENTITY UNDER THE LICENSE AGREEMENT DURING THE ONE (1) YEAR PERIOD IMMEDIATELY PRECEDING THE DATE THE CAUSE OF ACTION AROSE.</P>
<P class="p68 ft4">Section 5.5 <SPAN class="ft8">Exclusion of Consequential Damages</SPAN>. <SPAN class="ft3">IN NO EVENT SHALL COMPANY HAVE ANY LIABILITY TO COVERED ENTITY FOR ANY INDIRECT, SPECIAL, INCIDENTAL, PUNITIVE, OR CONSEQUENTIAL DAMAGES HOWEVER CAUSED AND, WHETHER IN CONTRACT, TORT OR UNDER ANY OTHER THEORY OF LIABILITY WHETHER OR NOT COMPANY HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. BECAUSE SOME STATES OR JURISDICTIONS DO NOT ALLOW THE EXCLUSION OR THE LIMITATION OF LIABILITY FOR</SPAN></P>
<P class="p69 ft5">CONSEQUENTIAL OR INCIDENTAL DAMAGES, IN SUCH STATES OR</P>
<P class="p28 ft2">JURISDICTIONS, COMPANY’S LIABILITY SHALL BE LIMITED TO THE MAXIMUM</P>
<P class="p28 ft1">EXTENT PERMITTED BY LAW.</P>
<P class="p70 ft3"><SPAN class="ft4">Section 5.6 </SPAN><SPAN class="ft8">Counterparts; Facsimiles. </SPAN>This BA Agreement may be executed in any number of counterparts, which may be delivered by facsimile or other electronic transmission, including email, each of which shall be deemed an original.</P>
<P class="p71 ft0">[Remainder of page left intentionally blank]</P>
</DIV>
<DIV id="page_7">


<P class="p7 ft1"><SPAN class="ft0">IN WITNESS WHEREOF, </SPAN>the parties hereto have duly executed this BA Agreement.</P>
<P class="p72 ft6">Mole Check App Inc.</P>
<P class="p59 ft1">Signature: Ben Behnam</P>
<P class="p72 ft1">Name: Ben Behnam MD</P>
<P class="p6 ft1">Title: Medical Director</P>
<P class="p72 ft1">Date: <?php echo FormatDate::AmericanFormat(date('Y-m-d')) ?></P>
<P class="p73 ft6">Covered Entity</P>
<P class="p59 ft1">Signature: <?php echo $doctor->signature ?></P>
<P class="p72 ft1">Name: <?php echo $doctor->firstname. " " . $doctor->lastname . " " . Doctor::getDoctorType($doctor->doctor_type) ?></P>
<P class="p72 ft1">Title: Physician</P>
<P class="p6 ft1">Date: <?php echo FormatDate::AmericanFormat(date('Y-m-d')) ?></P>
</DIV>
</BODY>
</HTML>
