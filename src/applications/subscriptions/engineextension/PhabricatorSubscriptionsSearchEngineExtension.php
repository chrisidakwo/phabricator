<?php

final class PhabricatorSubscriptionsSearchEngineExtension
  extends PhabricatorSearchEngineExtension {

  const EXTENSIONKEY = 'subscriptions';

  public function isExtensionEnabled() {
    return PhabricatorApplication::isClassInstalled(
      'PhabricatorSubscriptionsApplication');
  }

  public function getExtensionName() {
    return pht('Support for Subscriptions');
  }

  public function getExtensionOrder() {
    return 1000;
  }

  public function supportsObject($object) {
    return ($object instanceof PhabricatorSubscribableInterface);
  }

  public function applyConstraintsToQuery(
    $object,
    $query,
    PhabricatorSavedQuery $saved,
    array $map) {

    if (!empty($map['subscriberPHIDs'])) {
      $query->withEdgeLogicPHIDs(
        PhabricatorObjectHasSubscriberEdgeType::EDGECONST,
        PhabricatorQueryConstraint::OPERATOR_OR,
        $map['subscriberPHIDs']);
    }
  }

  public function getSearchFields($object) {
    $fields = array();

    $fields[] = id(new PhabricatorSearchSubscribersField())
      ->setLabel(pht('Subscribers'))
      ->setKey('subscriberPHIDs')
      ->setConduitKey('subscribers')
      ->setAliases(array('subscriber', 'subscribers'));

    return $fields;
  }


}
