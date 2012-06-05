// WARNING: DO NOT EDIT THIS FILE. THIS FILE IS MANAGED BY SPRING ROO.
// You may push code into the target .java compilation unit if you wish to edit any member(s).

package net.triptech.tripdocs.model;

import javax.persistence.EntityManager;
import javax.persistence.TypedQuery;
import net.triptech.tripdocs.model.Person;

privileged aspect Person_Roo_Finder {
    
    public static TypedQuery<Person> Person.findPeopleByOpenIdIdentifier(String openIdIdentifier) {
        if (openIdIdentifier == null || openIdIdentifier.length() == 0) throw new IllegalArgumentException("The openIdIdentifier argument is required");
        EntityManager em = Person.entityManager();
        TypedQuery<Person> q = em.createQuery("SELECT o FROM Person AS o WHERE o.openIdIdentifier = :openIdIdentifier", Person.class);
        q.setParameter("openIdIdentifier", openIdIdentifier);
        return q;
    }
    
}