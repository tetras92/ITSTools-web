<html>
<?php include 'header.php'; ?>

<h1>Using ITS modeler to design and analyze systems using discrete Time Petri nets.</h1>
<p>
	The ITS Modeler front-end for its-tools can be used to analyze Time Petri nets, and some effort has
	been invested in providing a user-friendly GUI and compatibility with
	the major TPN tools <a href="http://projects.laas.fr/tina/">Tina</a>
	and <a href="http://romeo.rts-software.org/">Romeo</a>. ITS tools and
	the graphical front-end are available for all major platforms (Windows,
	MacOS X, linux).
</p>

<a name="toc"></a>
<?php TableOfContents(__FILE__, 4); ?>


<h2><a name="sec:Install"></a>I. Install </h2>

<p>Please follow <a href="itstools.php#sec:modinst">these guidelines</a> to install ITS modeler.</p>
<p>A set of examples is provided embedded in the distribution. After install, use "New->Example...->Coloane Example->" and select any of the examples with a small clock designating Time Petri nets.</p>


<h2><a name="sec:TPNeditor"></a>II. Using the Time Petri net editor</h2>

<h3><a name="ssec:modelTPN"></a>1. Modeling with Discrete Labeled TPN</h3>
<p>Create an empty project: "File->new Project->Coloane->Modeling
	Project", Next, give a name, finish.</p>
<p>In this project create a new TPN model :
	"New->Other...->Coloane->Model", select the TPN formalism, and provide
	a name.</p>
<img src="images/newtpn.jpg" alt="download" />

<p>You can use the palette to create new objects. Just make sure you
	have the "Properties" view open, so you can edit names and attributes
	of the selected objects. The editor itself is feature rich thanks to
	Coloane, and supports all the usual cut/copy/paste, zoom, inflection
	points on arcs, guides and rulers to help align objects, graphical
	properties (color, size...) etc... There are quite a few keyboard
	shortcuts (Ctrl-C, Ctrl-V), toolbar buttons and right-click contextual
	actions available in the editor, which are not described in detail
	here. Float over the buttons to get a help tip. We recommend you switch
	to the Coloane perspective (button "Open perspective->Other->Coloane
	Modeler" in top right of Eclipse) to get the appropriate views
	(properties, problems, outline) and buttons available.</p>
<img src="images/tpn.jpg" alt="download" />

<p>The formalism used is Discrete Time Labeled Petri Nets with
	inhibitor, test and reset arcs as well as the more classsic
	input/output arcs. The elements available for modeling are described
	below :</p>
<img src="images/TPNfeatures.png" alt="download" />

<p>
<ol>
	<li>Places :
		<ul>
			<li>Name : a name for the place, used to query the place marking
				in the logic.</li>
			<li>Marking : an integer expression defining the place initial
				marking. Small values are represented as black dots in the place.</li>
		</ul>
	</li>
	<li>Transitions :
		<ul>
			<li>Label : a label for this transition, which is semantic if
				the transition is "public". The label may also be used in counter
				example traces.</li>
			<li>Visibility : either Public or Private. Private transitions
				do not export their label, and may be fired any time they are
				locally enabled. Public transitions cannot be fired unless triggered
				by an outside synchronization, that targets the appropriate label.
				Public transitions are graphically represented with a double border.
				When several transitions bear the same public label "l", asking this
				TPN to fire "l" requires ANY ONE OF the transitions labeld "l" to
				fire.</li>
			<li>Earliest and Latest Firing times : a clock is implicitly
				started as soon a transition is enabled; all transition clocks
				progress by one unit at the same time. The transition may only fire
				if the current clock value is within the range [earliest, latest].
				When the clock reaches the latest firing time, it becomes urgent and
				must be fired (or disabled by another transition firing) before time
				can elapse again. By default, the earliest firing time is 0 (the
				transition is enabled as soon as it is marking enabled) and the
				latest firing time is infinity noted "inf" in the syntax (there is
				no urgency to the transition). Both time bounds are inclusive, a
				transition marked [2,2] can only fire when its clock reaches two.</li>
		</ul>
	</li>
	<li>Arc types
		<ol>
			<li>Input Arc : carries an integer expression "value"
				<ul>
					<li>Enabling : marking of connected place Greater or Equal to
						"value"</li>
					<li>Fire : decrease marking of connected place by "value"</li>
				</ul>
			</li>
			<li>Output Arc : carries an integer expression "value"
				<ul>
					<li>Enabling : always</li>
					<li>Fire : increase marking of connected place by "value".</li>
				</ul>
			</li>
			<li>Test Arc : (also called read arc in the litterature) carries
				an integer expression "value"
				<ul>
					<li>Enabling : marking of connected place Greater or Equal to
						"value"</li>
					<li>Fire : no effect</li>
				</ul>
			</li>
			<li>Inhibitor Arc : carries an integer expression "value"
				<ul>
					<li>Enabling : marking of connected place strictly less than
						"value"</li>
					<li>Fire : no effect</li>
				</ul>
			</li>
			<li>Reset Arc :
				<ul>
					<li>Enabling : always</li>
					<li>Fire : set marking of connected place to zero.</li>
				</ul>
			</li>
		</ol>
	</li>
</ol>
</p>

<p>Places and transitions also have a "Component" attribute, which
	can be optionally be set to an integer. This attribute is not semantic
	and can be safely left blank. It is used to cluster objects that have
	the same component when doing auto-layout with dot and also allows
	ascendant compatibility with the historically supported notion of
	"subcomponents" in a net.</p>

<p>Beware of timed transitions which are also declared "Public". The
	clock is local to the current TPN, and starts counting as soon as the
	transition is locally enabled, even if other participants in the
	synchronization are not ready. It is preferable to avoid clocks on
	public transitons altogether, or else ensure that if the transition is
	locally enabled then it is necessarily globally enabled. Otherwise
	time-locks could occur, i.e. states where time elapse cannot occur
	without any urgent transitions being enabled (because the public
	transition is locally urgent but cannot globally fire).</p>

<div class="toplink" align="right">
	<a href="#toc">Start of page <img alt="" src="images/up.gif"
		width="13" height="12" border="0"></a>
</div>

<h3><a name="ssec:confplugin"></a>2. Integer expressions</h3>

<h4><a name="ssc:vars"></a>a) Using variables</h4>
<p>The tool supports the use of variables and expressions in the
	annotations of the net. This allows a parametric definition of a net,
	the parameters are then fixed separately prior to a given verification
	run. Variables are introduced by using the $ sign, e.g. $a for a
	variable "a". Variables have the scope of the whole net, so if you use
	the same variable in various expressions you get only one parameter.</p>

<p>The syntax of integer expressions supports basic arithmetic
	operators (+, , -, *, /, %) and parenthesis. The tool includes a syntax
	check that will raise errors on malformed expressions. Open the
	"Problems" view ("Window->Show View->Problems" if it is not already
	available) to see these erros.</p>

<p>This controller from the Train crossing example (Get it in
	eclipse through : "File->New...->Example->Coloane->TrainCrossing") is a
	good example of using this feature.</p>
<img src="images/intExpr.png" alt="download" />
<p>In this example, the initial marking of "far" is $N, and tokens
	will move from "far" to "near" and back again. The transition
	"EnterFirst" can only be fired if all $N tokens are in "far", and it
	then puts one token in "near" and puts all the "$N-1" other tokens back
	in place "far". A contrario, "Enter" can only fire if there is at least
	a token in "near", so the two transitions are enabled in a
	complementary fashion.</p>

<h4><a name="ssc:varval"></a>b) Defining parameter values</h4>
<p>The values of these parameters are set up separately, and are
	fixed throughout a run, so this mechanism is close to macro
	substitution. To setup the parameter values, you need to create a new
	ITS referential that wraps the TPN into ITS. Use
	"File->New->Other->Coloane->ITS Composition model" and give it a name.</p>

<p>Then import your TPN into the referential, by clicking the "Add
	type" button or from the explorer on the left drag and drop the
	model(s) you wish to analyze into the "Types Declaration" frame.</p>
<img src="images/dragdrop.jpg" alt="download" />

<p>If you select the newly imported model, you will see it has
	children which are the parameters "$a,..." used in the model
	definition. Once you have provided a value for all parameters, the icon
	changes to show it is fully defined.</p>
<img src="images/setParam.png" alt="download" />

<p>
	<a name="pp:flat"></a>If your purpose is to exchange the model with
	other tools such as Tina or Romeo, or you simply wish to look at the
	instantiated model, you can select the model and click "Flatten Model",
	then select a name for new model and make sure you check the box
	"instantiate variables". The ITS tools don't require models to be
	flattened/instantiated before analysis.
</p>
<img src="images/flattenVar.png" alt="download" />

<div class="toplink" align="right">
	<a href="#toc">Start of page <img alt="" src="images/up.gif"
		width="13" height="12" border="0"></a>
</div>

<h3><a name="ssec:iotpn"></a>3. Model Exchange (Tina, Romeo)</h3>

<p>
	Supporting model exchange across tools is important and useful,
	especially since Romeo and Tina use different techniques from each
	other and from ITS tools to perform analysis. We support import and
	export of <a href="http://projects.laas.fr/tina/">Tina</a> and <a
		href="http://romeo.rts-software.org/">Romeo</a> models of TPN.
</p>
<p>Import of Romeo .xml format and of the .net format (no positions)
	and .ndr format (with positions) of Tina are possible.</p>
<p>Models can be easily imported from Romeo or Tina, just use the
	"File->Import->Coloane" menu and select the file type. See example
	screenshot.</p>
<img src="images/import.jpg" alt="download" />

<p>Similarly, Export is available by right-clicking the target
	.model file and selecting Export, then the target format.</p>

<p>Beware that Tina does not support all features of our TPN, mainly 
   Reset arcs are not supported. Test arcs are translated to
	a pair of input/output arcs. Romeo supports all the features we
	propose, as well as hyper-arcs which are supported by the ITS
	model-checking tools, but not by the graphical front-end.</p>

<p>The export will export also any public transitions, thus
	semantics are not quite equivalent to an ITS (where public transitions
	are not fireable in isolation).</p>

<p>Both Tina and Romeo use dense time semantics, while ITS tools use
	discrete time. Dense and discrete semantics coincide for the type of
	time bounds we support (inclusive only). The export is thus fine from
	this point of view (exported Tina/Romeo models agree with ITS models),
	but the import will interpret open time bounds (exclusive) as if they
	were closed (inclusive), which may affect the semantics (i.e. ITS tools
	analysis on imported model may provide different results from
	Tina/Romeo). Tina can also work with discrete time assumptions, if you
	 use the flag "-F1" (exactly equivalent to our own analysis) or "-D" 
	 (discrete time but using Popova "essential states" abstraction)</p>

<p>Naming of places and transitions is also an issue, because 
   place and trnasition names are not necessarily unique, but they serve 
   as identifiers for Tina. Hence the exported names will have a prefix with
    a unique integer identifier.
   Useful features of both Tina and Romeo include interactive simulation, that
	helps debug models. Note that on most OS, if Tina and/or Romeo are
	installed and appropriate file extension associations have been
	declared, exported models can be opened by those tools directly from
	Eclipse by double-clicking on them in the explorer on the left. A
	richt-click on the file "Open with..." can also allow this behavior.</p>

<p>
	If your purpose is to export ITS models (i.e. with Composite, Scalar or
	Circular types described <a href="composite.php">here</a>)to Tina or Romeo, you can in most cases
	flatten them. The "Flatten Model" action (<a href='#pp:flat'>described
		above</a>) will recursively descend into composite definitions to build a
	single Petri net. This TPN can then be exported with
	"right-click...Export->Coloane->...".
</p>


<div class="toplink" align="right">
	<a href="#toc">Start of page <img alt="" src="images/up.gif"
		width="13" height="12" border="0"></a>
</div>


<!-- #EndEditable -->
<?php include 'footer.php'; ?>

</html>
