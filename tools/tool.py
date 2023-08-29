import ldb
from samba import dsdb
from samba.auth import system_session
from samba.credentials import Credentials
from samba.param import LoadParm
from samba.samdb import SamDB

class SambaTool:
    def __init__(self):
        lp = LoadParm()
        creds = Credentials()
        creds.guess(lp)
        self.samdb = SamDB(
            url='/var/lib/samba/private/sam.ldb',
            session_info=system_session(),
            credentials=creds,
            lp=lp
        )
        self.domaindn = self.samdb.domain_dn()

    def search(self, query):
        result = self.samdb.search(
            base=self.domaindn,
            expression=query,
            scope=ldb.SCOPE_SUBTREE
        )
        return result

    def group2gid(self, groupname):
        query = ("(&(objectCategory=group)(sAMAccountName=%s))"%
                    ldb.binary_encode(groupname))

        result = self.search(query)
        if (result.count): return str(result[0]['gidNumber'])
        raise Exception("grupo %s não encontrado."%groupname)

    def find_user(self, username):
        query = ("(&(sAMAccountType=%d)(sAMAccountName=%s))" %
                  (dsdb.ATYPE_NORMAL_ACCOUNT, ldb.binary_encode(username)))

        result = self.search(query)
        if (result.count): return result[0]
        return None

    def add_user(self, account):
        tmp = account['name'].split()
        given_name = tmp.pop(0)
        surname = ' '.join(tmp)[:64]
        # surname não pode ser vazio
        if (surname == ''): surname = '.'
        gid = self.group2gid(account['group'])
        # tratar gid == None

        self.samdb.newuser(
            account['username'],
            account['password'],
            surname=surname,
            givenname=given_name,
            unixhome=account['home'],
            uid=account['username'],
            uidnumber=account['uid'],
            gidnumber=gid,
            loginshell='/bin/bash'
        )
