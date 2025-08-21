@extends('layout.auth')

@section('title','CProfile')
@section('auth-content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <h2 class="text-2xl font-bold mb-6 text-center">Informed Consent</h2>

    <!-- Consent Text (Scrollable Box) -->
    <div class="bg-white p-6 rounded-lg shadow-md max-h-[70vh] overflow-y-auto leading-relaxed text-justify">
        <p><strong>TREATMENT TO BE DONE</strong> I understand and consent to have any treatment done by the dentist after the procedure, the risks & benefits & cost have been fully explained. These treatments include, but are not limited to, x-rays, cleanings, periodontal treatments, fillings, crowns, bridges, all types of extraction, root canals, &/or dentures, local anesthetics & surgical cases.</p>

        <p class="mt-4"><strong>DRUGS & MEDICATIONS</strong> I understand that antibiotics, analgesics & other medications can cause allergic reactions like redness & swelling of tissues, pain, itching, vomiting, &/or anaphylactic shock. <em>Initial: ________</em></p>

        <p class="mt-4"><strong>CHANGES IN TREATMENT PLAN</strong> I understand that during treatment it may be necessary to change/add procedures because of conditions found while working on the teeth that was not discovered during examination. For example, root canal therapy may be needed following routine restorative procedures. I give my permission to the dentist to make any/all changes and additions as necessary w/ my responsibility to pay all the costs agreed.</p>

        <p class="mt-4"><strong>RADIOGRAPH:</strong> I understand that an x-ray shot or a radiograph maybe necessary as part of diagnostic aid to come up with tentative diagnosis of my Dental problem and to make a good treatment plan, but, this will not give me a 100% assurance for the accuracy of the treatment since all dental treatments are subject to unpredictable complications that later on may lead to sudden change of treatment plan and subject to new charges. <em>(Initial: ________)</em></p>

        <p class="mt-4"><strong>REMOVAL OF TEETH:</strong> I understand that alternatives to tooth removal (root canal therapy, crowns & periodontal surgery, etc.) & I completely understand these alternatives, including their risk & benefits prior to authorizing the dentist to remove teeth & any other structures necessary for reasons above. I understand that removing teeth does not always remove all the infections, if present, & it may be necessary to have further treatment. I understand the risk involved in having teeth removed, such as pain, swelling, spread of infection, dry socket, fractured jaw, loss of feeling on the teeth, lips, tongue & surrounding tissue that can fast for an indefinite period of time. I understand that I may need further treatment under a specialist if complications arise during or following treatment.</p>

        <p class="mt-4"><strong>CROWNS (CAPS) & BRIDGES:</strong> Preparing a tooth may irritate the nerve tissue in the center of the tooth, leaving the tooth extra sensitive to heat, cold & pressure. Treating such irritation may involve using special toothpastes, mouth rinses or root canal therapy. I understand that sometimes it is not possible to match the color of natural teeth exactly with artificial teeth. I further understand that I may be wearing temporary crowns, which may come off easily & that I must be careful to ensure that they are kept on until the permanent crowns are delivered. It is my responsibility to return for permanent cementation within 20 days from tooth preparation, as excessive days delay may allow for tooth movement, which may necessitate a remake of the crown, bridge/cap. I understand there will be additional charges for remakes due to my delaying of permanent cementation, & I realize that final opportunity to make changes in my new crown, bridges or cap (including shape, fit, size, & color) will be before permanent cementation.</p>

        <p class="mt-4"><strong>ENDODONTICS (ROOT CANAL)</strong> I understand there is no guarantee that a root canal treatment will save a tooth & that complications can occur from the treatment & that occasionally root canal filling materials may extend through the tooth which does not necessarily affect the success of the treatment. I understand that endodontic files & drills are very fine instruments & stresses vented in their manufacture & calcifications present in teeth can cause them to break during use. I understand that referral to the endodontist for additional treatments may be necessary following any root canal treatment & I agree that I am responsible for any additional cost for treatment performed by the endodontist. I understand that a tooth may require removal in spite of all efforts to save it.</p>

        <p class="mt-4"><strong>PERIODONTAL DISEASE:</strong> I understand that periodontal disease is a serious condition causing gum & bone inflammation &/or loss & that can lead eventually to the loss of my teeth. I understand the alternative treatment plans to correct periodontal disease, including gum surgery, tooth extractions with or without replacement. I understand that undertaking any dental procedures may have future adverse effects on my periodontal conditions.</p>

        <p class="mt-4"><strong>FILLINGS:</strong> I understand that care must be exercised in chewing on fillings, especially during the first 24 hours to avoid breakage. I understand that a more extensive filling or a crown may be required, as additional decay or fracture may become evident after initial excavation. I understand that significant sensitivity is a common, but usually temporary, after-effect of a newly placed filling. I further understand that filling a tooth may irritate the nerve tissue creating sensitivity & treating such sensitivity could require canal therapy or extractions.</p>

        <p class="mt-4"><strong>DENTURES:</strong> I understand that wearing of dentures can be difficult. Sore spots, altered speech & difficulty in eating are common problems. Immediate dentures (placement of denture immediately after extractions) may be painful. Immediate dentures may require considerable adjusting & several relines. I understand that it is my responsibility to return for delivery of dentures. I understand that failure to keep my delivery appointment may result in poorly fitted dentures. If a remake is required due to my delays of more than 30 days, there will be additional charges. A permanent reline will be needed later, which is not included in the initial fee. I understand that all adjustment or alterations of any kind after this initial period is subject to charges.</p>

        <p class="mt-4">I understand that dentistry is not an exact science and that no dentist can properly guarantee accurate results all the time.</p>

        <p class="mt-4">Thereby, I authorize any of the doctors/dental auxiliaries to proceed with & perform the dental restorations & treatments as explained to me. I understand that these are subject to modification depending on undiagnosable circumstances that may arise during the course of treatment. I understand that regardless of any dental insurance coverage I may have, I am responsible for payment of dental fees. I agree to pay any attorney's fees, collection fee, or court costs that may be incurred to satisfy any obligation to this office. All treatment were properly explained to me & any untoward circumstances that may arise during the procedure, the attending dentist will not be held liable since it is my free will, with full trust & confidence in him/her, to undergo dental treatment under his/her care.</p>
    </div>

    <!-- I Agree Button -->
    <form action="{{ route('consent.store') }}" method="POST" class="mt-6 text-center">
        @csrf
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
            I Agree
        </button>
    </form>
</div>
@endsection
