<?php

/**
 * Template form for attribute selection.
 *
 * Parameters:
 * - 'srcMetadata': Metadata/configuration for the source.
 * - 'dstMetadata': Metadata/configuration for the destination.
 * - 'yesTarget': Target URL for the yes-button. This URL will receive a POST request.
 * - 'yesData': Parameters which should be included in the yes-request.
 * - 'logoutLink': Where to redirect if the user aborts.
 * - 'logoutData': The Data to post to the logout link.
 * - 'sppp': URL to the privacy policy of the destination, or FALSE.
 *
 * @package SimpleSAMLphp
 */

assert('is_array($this->data["srcMetadata"])');
assert('is_array($this->data["dstMetadata"])');
assert('is_string($this->data["yesTarget"])');
assert('is_array($this->data["yesData"])');
assert('is_string($this->data["error_msg"])');
assert('is_string($this->data["logoutLink"])');
assert('is_array($this->data["logoutData"])');

assert('$this->data["sppp"] === false || is_string($this->data["sppp"])');

// Parse parameters

if (array_key_exists('name', $this->data['srcMetadata'])) {
    $srcName = $this->data['srcMetadata']['name'];
} elseif (array_key_exists('OrganizationDisplayName', $this->data['srcMetadata'])) {
    $srcName = $this->data['srcMetadata']['OrganizationDisplayName'];
} else {
    $srcName = $this->data['srcMetadata']['entityid'];
}

if (is_array($srcName)) {
    $srcName = $this->t($srcName);
}

if (array_key_exists('name', $this->data['dstMetadata'])) {
    $dstName = $this->data['dstMetadata']['name'];
} elseif (array_key_exists('OrganizationDisplayName', $this->data['dstMetadata'])) {
    $dstName = $this->data['dstMetadata']['OrganizationDisplayName'];
} else {
    $dstName = $this->data['dstMetadata']['entityid'];
}

if (is_array($dstName)) {
    $dstName = $this->t($dstName);
}

if (array_key_exists('error_msg', $this->data)) {
    $errorMsg = $this->data['error_msg'];
}

$this->data['jquery'] = array('core' => true, 'ui' => true, 'css' => true);
$this->data['head'] = '<link rel="stylesheet" type="text/css" href="/' . $this->data['baseurlpath']
. 'module.php/attrauthgocdb/resources/css/style.css" />' . "\n";
$this->includeAtTemplateBase('includes/header.php');
?>
    <p>
        <?php
        print('<h3 id="attributeheader">' . $this->t('{attrauthgocdb:attrauthgocdb:failure_text}') . '</h3>');
        ?>
        <p><?php print($this->t('{attrauthgocdb:attrauthgocdb:error_msg_title}')); ?></p>
        <div class="warning"><?php print($errorMsg) ?></div>
    </p>
    <!--  Form that will be sumbitted on Yes -->
    <form style="display: inline; margin: 0px; padding: 0px"
        action="<?php print(htmlspecialchars($this->data['yesTarget'])); ?>"
    >
        <p style="margin: 1em">
        <?php
        foreach ($this->data['yesData'] as $name => $value) {
            print(
                '<input type="hidden" name="' . htmlspecialchars($name)
                . '" value="' . htmlspecialchars($value) . '" />'
            );
        }
        ?>
        </p>
        <button type="submit" name="yes" class="btn" id="yesbutton">
        <?php print(htmlspecialchars($this->t('{attrauthgocdb:attrauthgocdb:yes}'))) ?>
        </button>
    </form>

    <!--  Form that will be submitted on cancel-->
    <form style="display: inline; margin-left: .5em;"
        action="<?php print htmlspecialchars($this->data['logoutLink']); ?>"
        method="get"
    >
        <?php
        foreach ($this->data['logoutData'] as $name => $value) {
            print(
                '<input type="hidden" name="' . htmlspecialchars($name)
                . '" value="' . htmlspecialchars($value) . '" />'
            );
        }
        ?>
        <button type="submit" class="btn" name="no" id="nobutton">
        <?php print(htmlspecialchars($this->t('{attrauthgocdb:attrauthgocdb:no}'))) ?>
        </button>
    </form>

<?php

if ($this->data['sppp'] !== false) {
    print("<p>" . htmlspecialchars($this->t('{attrauthgocdb:attrauthgocdb:attrauthgocdb_privacy_policy}')) . " ");
    print("<a target='_blank' href='" . htmlspecialchars($this->data['sppp']) . "'>" . $dstName . "</a>");
    print("</p>");
}

$this->includeAtTemplateBase('includes/footer.php');
